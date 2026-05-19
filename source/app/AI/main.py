from fastapi import FastAPI
from sqlalchemy import create_engine
import pandas as pd
from sklearn.linear_model import LinearRegression
import numpy as np

app = FastAPI()

# Update with your MySQL connection info
engine = create_engine(
    "mysql+pymysql://root:pass@localhost:3307/dormdash_db_v4"
)

@app.get("/forecast/revenue")
def revenue_forecast():
    """
    Forecast daily revenue using linear regression.
    Returns predicted revenue for the next day and historical data.
    """
    query = """
SELECT
    DATE(o.created_at) AS order_date,
    SUM(oi.price * oi.quantity) AS revenue
FROM orders o
JOIN order_items oi ON o.order_id = oi.order_id
JOIN items i ON oi.item_id = i.item_id
WHERE i.vendor_id = 30
GROUP BY DATE(o.created_at)
ORDER BY order_date;
    """

    df = pd.read_sql(query, engine)

    if df.empty or len(df) < 2:
        # Not enough data to forecast
        return {
            "predicted_revenue": 0.0,
            "historical": df.to_dict(orient="records")
        }

    # Use day index as the feature
    df = df.sort_values("order_date").reset_index(drop=True)
    df['day_index'] = np.arange(len(df))

    X = df[['day_index']]
    y = df['revenue']

    # Train linear regression
    model = LinearRegression()
    model.fit(X, y)

    # Predict revenue for next day
    next_day_index = np.array([[len(df)]])
    prediction = model.predict(next_day_index)

    return {
        "predicted_revenue": float(max(prediction[0], 0)),  # avoid negative
        "historical": df.to_dict(orient="records")
    }

@app.get("/inventory/alerts")
def inventory_alerts(threshold: int = 10):
    """
    Return items with stock below threshold.
    Default threshold is 10.
    """
    # Query daily item revenue for the vendor
    start_date = (datetime.utcnow() - timedelta(days=days)).date()

    query = text("""
        SELECT
            DATE(o.created_at) AS order_date,
            oi.item_id,
            SUM(oi.price * oi.quantity) AS revenue
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN items i ON oi.item_id = i.item_id
        WHERE i.vendor_id = :vendor_id
          AND DATE(o.created_at) >= :start_date
        GROUP BY oi.item_id, DATE(o.created_at)
        ORDER BY oi.item_id, order_date
    """)

    with engine.connect() as conn:
        df = pd.read_sql(query, conn, params={"vendor_id": vendor_id, "start_date": start_date})

    if df.empty:
        return []

    # Pivot to have one column per item
    pivot = df.pivot_table(index='order_date', columns='item_id', values='revenue', aggfunc='sum').fillna(0)

    # Compute total revenue per item and pick top
    totals = pivot.sum(axis=0).sort_values(ascending=False)
    top_items = totals.head(top).index.tolist()

    results = []
    for item_id in top_items:
        series = pivot[item_id].reset_index(drop=True)
        X = np.arange(len(series)).reshape(-1, 1)
        y = series.values

        if len(y) < 2:
            pred = [0.0 for _ in range(horizon)]
        else:
            model = LinearRegression().fit(X, y)
            future_idx = np.arange(len(series), len(series) + horizon).reshape(-1, 1)
            pred = [round(float(max(p, 0.0)), 2) for p in model.predict(future_idx)]

        results.append({
            'item_id': int(item_id),
            'predicted': pred,
            'total_predicted': round(float(sum(pred)), 2)
        })

    # Sort results by total_predicted descending to match the UI description
    results.sort(key=lambda x: x['total_predicted'], reverse=True)

    return results


@app.get('/forecast/summary')
def forecast_summary(vendor_id: int = Query(...), days: int = Query(90), horizon: int = Query(1)):
    """
    Return a compact summary: predicted next day revenue, last day revenue, and percent change.
    """
    df = _daily_revenue_df(vendor_id, days)

    if df.empty or len(df) < 2:
        return {
            'predicted_next_day': 0.0,
            'last_day': 0.0,
            'percent_change': 0.0
        }

    df = df.sort_values('order_date').reset_index(drop=True)
    X = np.arange(len(df)).reshape(-1, 1)
    y = df['revenue'].values

    model = LinearRegression().fit(X, y)
    next_idx = np.array([[len(df)]])
    pred = float(max(model.predict(next_idx)[0], 0.0))
    last = float(df['revenue'].iloc[-1])
    pct = ((pred - last) / last * 100) if last != 0 else 0.0

    return {
        'predicted_next_day': round(pred, 2),
        'last_day': round(last, 2),
        'percent_change': round(pct, 2)
    }


@app.get('/inventory/alerts')
def inventory_alerts(vendor_id: int = Query(...), threshold: int = Query(default=10, ge=0)):
    query = text("""
        SELECT
            i.item_id,
            i.name,
            i.stock
        FROM items i
        WHERE i.vendor_id = :vendor_id
          AND i.stock < :threshold
        ORDER BY i.stock ASC
    """)

    with engine.connect() as conn:
        df = pd.read_sql(query, conn, params={"vendor_id": vendor_id, "threshold": threshold})

    return df.to_dict(orient='records')


@app.get('/health')
def health():
    return {"status": "ok"}

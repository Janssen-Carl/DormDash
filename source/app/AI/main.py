from fastapi import FastAPI, Query
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy import create_engine, text
import pandas as pd
from sklearn.linear_model import LinearRegression
import numpy as np
from datetime import datetime, timedelta
import os

app = FastAPI(title="DormDash AI Analytics API")

# Allow CORS from localhost/dev UI
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Use env var if provided, fallback to previous value
DB_URL = os.environ.get('AI_DB_URL') or "mysql+pymysql://root:pass@localhost:3307/dormdash_db_v4"
engine = create_engine(DB_URL)


def _daily_revenue_df(vendor_id: int, days: int = 90):
    # compute start_date
    start_date = (datetime.utcnow() - timedelta(days=days)).date()

    query = text("""
        SELECT
            DATE(o.created_at) AS order_date,
            SUM(oi.price * oi.quantity) AS revenue
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN items i ON oi.item_id = i.item_id
        WHERE i.vendor_id = :vendor_id
          AND DATE(o.created_at) >= :start_date
        GROUP BY DATE(o.created_at)
        ORDER BY order_date
    """)

    with engine.connect() as conn:
        df = pd.read_sql(query, conn, params={"vendor_id": vendor_id, "start_date": start_date})

    return df


@app.get("/forecast/revenue")
def revenue_forecast(vendor_id: int = Query(...), days: int = Query(90), horizon: int = Query(1)):
    """
    Forecast daily revenue using linear regression for a given vendor.
    Returns predicted revenue for the next `horizon` days and historical data.
    """
    df = _daily_revenue_df(vendor_id, days)

    if df.empty or len(df) < 2:
        return {
            "predicted": [0.0 for _ in range(horizon)],
            "dates": [ (datetime.utcnow() + timedelta(days=i+1)).strftime('%Y-%m-%d') for i in range(horizon) ],
            "historical": []
        }

    df = df.sort_values("order_date").reset_index(drop=True)
    df['day_index'] = np.arange(len(df))

    X = df[['day_index']].values
    y = df['revenue'].values

    model = LinearRegression()
    model.fit(X, y)

    future_idx = np.arange(len(df), len(df) + horizon).reshape(-1, 1)
    preds = model.predict(future_idx)

    preds = [round(float(max(p, 0.0)), 2) for p in preds]
    hist = df.assign(order_date=df['order_date'].astype(str)).to_dict(orient='records')

    return {
        "predicted": preds,
        "dates": [ (datetime.utcnow() + timedelta(days=i+1)).strftime('%Y-%m-%d') for i in range(horizon) ],
        "historical": hist
    }


@app.get("/forecast/items")
def item_forecasts(vendor_id: int = Query(...), days: int = Query(90), horizon: int = Query(1), top: int = Query(5)):
    """
    Forecast revenue for top N items for the next `horizon` days.
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

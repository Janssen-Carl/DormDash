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
    query = f"""
    SELECT
        item_id,
        name,
        stock
    FROM items
    WHERE stock < {threshold}
    ORDER BY stock ASC
    """

    df = pd.read_sql(query, engine)

    return df.to_dict(orient="records")

@app.get("/health")
def health():
    return {"status": "ok"}

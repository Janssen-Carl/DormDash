from fastapi import FastAPI, Query
from sqlalchemy import create_engine, text
import pandas as pd
from sklearn.linear_model import LinearRegression
import numpy as np

app = FastAPI(title="DormDash AI Analytics API")

# Update with your MySQL connection info
engine = create_engine(
    "mysql+pymysql://root:pass@localhost:3307/dormdash_db_v4"
)

@app.get("/forecast/revenue")
def revenue_forecast(vendor_id: int = 30):
    """
    Forecast daily revenue using linear regression.
    Returns predicted revenue for the next day and historical data for a specific vendor.
    """
    # Using text() with bound parameters to prevent any security issues
    query = text("""
        SELECT
            DATE(o.created_at) AS order_date,
            SUM(oi.price * oi.quantity) AS revenue
        FROM orders o
        JOIN order_items oi ON o.order_id = oi.order_id
        JOIN items i ON oi.item_id = i.item_id
        WHERE i.vendor_id = :vendor_id
        GROUP BY DATE(o.created_at)
        ORDER BY order_date;
    """)

    # Open connection context cleanly
    with engine.connect() as conn:
        df = pd.read_sql(query, conn, params={"vendor_id": vendor_id})

    if df.empty or len(df) < 2:
        return {
            "predicted_revenue": 0.0,
            "historical": []
        }

    # Prepare index features
    df = df.sort_values("order_date").reset_index(drop=True)
    df['day_index'] = np.arange(len(df))

    X = df[['day_index']]
    y = df['revenue']

    # Linear Regression Model
    model = LinearRegression()
    model.fit(X, y)

    # Predict next sequence index
    next_day_index = np.array([[len(df)]])
    prediction = model.predict(next_day_index)

    # Convert order_date to string format for clean frontend JSON serialization
    df['order_date'] = df['order_date'].astype(str)

    return {
        "predicted_revenue": round(float(max(prediction[0], 0)), 2),
        "historical": df.to_dict(orient="records")
    }

@app.get("/inventory/alerts")
def inventory_alerts(vendor_id: int = 30, threshold: int = Query(default=10, ge=0)):
    """
    Return items matching a specific vendor ID with stock below the designated threshold.
    """
    query = text("""
        SELECT
            item_id,
            name,
            stock
        FROM items
        WHERE vendor_id = :vendor_id AND stock < :threshold
        ORDER BY stock ASC
    """)

    with engine.connect() as conn:
        df = pd.read_sql(query, conn, params={"vendor_id": vendor_id, "threshold": threshold})

    return df.to_dict(orient="records")

@app.get("/health")
def health():
    return {"status": "ok"}

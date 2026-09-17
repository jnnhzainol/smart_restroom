# Railway deployment

The app uses the existing Railway MySQL service via MYSQLHOST, MYSQLPORT,
MYSQLUSER, MYSQLPASSWORD and MYSQLDATABASE. Local XAMPP defaults remain supported.
The deployment initializes missing tables without importing local accounts or readings.
The database volume persists registrations and readings across deployments.

Set APP_ENV=production, PORT=80 and a random SENSOR_API_KEY in Railway.
Configure the ESP32 to upload to https://<public-domain>/api/update_sensor.php
with its waste percentage and the X-Sensor-Key request header matching SENSOR_API_KEY.
The local endpoint remains unchanged. Soap readings currently retain the original
app's fixed 100% placeholder; a soap sensor integration is not implemented.

The production image excludes the default-password create_admin.php script and
SQL dumps. Apache denies access to configuration and deployment internals.

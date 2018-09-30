# AirPi: air quality station with the Raspberry Pi and the PM5003 sensor

https://www.rigacci.org/wiki/doku.php/doc/appunti/hardware/raspberrypi_air

A monitoring station for air quality based on the Raspberry Pi

## pms5003

The **lib/pms5003** is a Python script to read data from the 
PMS5003 particulate matter sensor by Plantower. It uses the 
serial line. It is rather complicated because it has all the 
following features:

* Handle sleep-down and awake of the sensor.
* Wait some time before read, allow the sensor to settle.
* Multiple read with average calculation.
* Verify data checksum.
* Handle communication errors.
* Single read or endless loop.
* Write data to status file (STATUS_FILE).
* Log to stdout/file/syslog.

The **Single Read Mode** is suitable for a cronjob: set 
SLEEP_BETWEEN_READS to -1. The sensor will be awakened before 
the reading, and it will be put at sleep before program exit.

For the **Endless Loop Mode** set SLEEP_BETWEEN_READS to the 
acquiring interval (seconds). If the interval is greather than 
three times the sensor's settling time, the sensor will be put 
to sleep before the next read.

==========================
BuildDemo
Build script for demo site
==========================

Use this script to run sql files in sequence mode.
This is useful if you want to setup demo site.

Becareful, this script contain function to drop all
database tables.

How to use:
Edit build.php, modify the following section:

  $debug .= drop_tables();
  $debug .= run_sql('fresh.sql');
  $debug .= run_sql('sampledb.sql');
  $debug .= run_sql('fixdb.sql');
  $debug .= run_sql('finalize.sql');
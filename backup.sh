mkdir -p ~/backups && cd ~/backups
STAMP=$(date +%Y%m%d_%H%M)

mysqldump --single-transaction --routines --triggers \
  mohaaseb_central > central_$STAMP.sql

# every tenant database, driven off the actual list
for DB in $(mysql -u mohaaseb -p -N -B -e \
    "SHOW DATABASES LIKE 'mohaaseb\_%';" | grep -v '^mohaaseb_central$'); do
  echo "dumping $DB"
  mysqldump --single-transaction --routines --triggers "$DB" > "${DB}_$STAMP.sql"
done

ls -lh *_$STAMP.sql

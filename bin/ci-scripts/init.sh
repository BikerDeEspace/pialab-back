#!/usr/bin/env bash
set -ex

# INIT .ENV FILE
if [ -f .env ]
then
    source .env
else
    echo "Please run this script from project root, and check .env file as it is mandatory"
    echo "If it is missing a quick solution is :"
    echo "ln -s .env.dist .env"
    exit 42
fi

# INIT DB SCRIPT
## CREATE DATABASE
bin/console doctrine:migrations:migrate --no-interaction

## START SERVER
#bin/console server:start

export PGPASSWORD=${DBPASSWORD}
## CREATE DEFAULT USER
userexist=$(psql -qt --no-align -w -h ${DBHOST} -c "select count(*) from pia_user where email='lici@pialab.io';" -U ${DBUSER} -d ${DBNAME}  )
if [ $userexist -eq 0 ] && [ ${CREATE_USER} == "TRUE" ]
then
    bin/console pia:user:create ${USER_MAIL} ${USER_PASSWORD} 
    bin/console pia:user:promote lici@pialab.io --role=ROLE_SUPER_ADMIN
fi

## CREATE DEFAULT APP
appexist=$(psql -qt --no-align -w -h ${DBHOST} -c "select count(*) from oauth_client where name='Default App';" -U ${DBUSER} -d ${DBNAME}  )
if [ $appexist -eq 0 ] && [ ${CREATE_APP} == "TRUE" ]
then
    bin/console pia:application:create --name="${CLIENT_NAME}" --url="${CLIENTURL}" --client-id=${CLIENT_ID} --client-secret=${CLIENT_SECRET}
fi

#POST INSTALL
bin/console assets:install --no-interaction
bin/console cache:warmup --no-interaction

# END INIT SCRIPT
## START APACHE
/usr/sbin/service apache2 restart && tail -f /var/log/apache2/error.log
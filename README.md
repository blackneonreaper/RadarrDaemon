# RadarrDaemon
Autosearch old movies not in RSS feeds, and remove cutoff reached movies

## Setup

### 1) Install packages
```
composer install
```
### 2) Update .env
```
RADARR_ENDPOINT='http://yourradarrlocation:7878/api/v3/'
RADARR_APIKEY='yourapikey'
SEARCH_AMOUNT=10
MAX_DOWNLOADING=20
EXCLUDE_FROM_IMPORT=1
DELETE_FILES=0
```
Update the parameters to connect to your radarr endpoint.  
You can find the apiKey in the settings of your radarr instance.

### 3) Install packages
Add the command to a crontab
```
0 */4 * * * php /locationToDaemon/RadarrDaemon/radarr.php maintenance
0 * * * * php /locationToDaemon/RadarrDaemon/radarr.php moviesearch
```

## Usage

### 1) maintenance
```
php radarr.php maintenance
```
This command will remove all the movies that have met their set cutoff quality.
The `EXCLUDE_FROM_IMPORT` value will make sure the movies arent added again when indexing your movie folder.  
The `DELETE_FILES` value will make sure the files aren't actually remove.  

**![#f03c15](https://via.placeholder.com/15/f03c15/000000?text=+) You could lose all your data if you set this value to 1**
### 1) Moviesearch
```
php radarr.php moviesearch
```
This command will search your movies that have not reached their cutoff quality.  
The `SEARCH_AMOUNT` value is the amount of movies that will be searched each time the command is used.  
The `MAX_DOWNLOADING` value is the amount of movies that your download client will download at the same time.
The search amount will be adjusted if this limit has been reached.  




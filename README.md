# Sorting APP

### Sorting translations in the resource file

```
product.TRDMCL.shortdescription.listitem3=Rubber clip adapts to collar
cms.tracker-comparison.seo.meta-title=Compare the Features of Tractive GPS Trackers
# https://tractive.com/en/c/shipping-costs
#
# lh-check { max: 60 }
cms.shipping-costs.seo.meta-title=Shipping Costs and Delivery for Tractive GPS Trackers
jobs.open-positions.heading=Your career at Tractive
team.section2-4.title=Marketing
general.action.add-to-cart=Add to Cart
# Button that links to https://my.tractive.com/#/activate/device
navigation.header.activateTracker=Activate Tracker
```
## Assumptions
1. The input file will be provided by the end user.
2. Only one input file  will be processed at a time.
3. The output file path will be displayed in the terminal after successful execution.

## How to install
Extract the zip folder and navigate to the project directory in the terminal
Run "composer install" to install the dependencies or run  "composer update" to update the dependencies

```bash
composer install
```

**Note:** Require PHP7.4 or later versions. This application was tested and executed on the PHP7.4 version.

## Generate autoload files

Run the following command in the project directory to generate optimized autoload files 

```bash
composer dump -o
```

## How to execute

```bash
php index.php sort input_file_path
```
**Note:**  "sort" is the application command. "input_file_path" is the path to the input file which is containing the unsorted translations. The output file path will be displayed in the terminal after successful execution.

## sample  command

```bash
php index.php sort /var/www/html/tractive_sorting_app/assets/translations.properties
```

## How to run unit tests

Execute the following command from the project root directory after installing the dependencies listed in composer.json

```bash
vendor/bin/phpunit
```

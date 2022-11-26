# JASPER REPORTS

### Dynamically create Reports using jrxml file and Laravel.

##### Installation
To install dynamic jasper reports:

```
composer require anshul-netgen/jasper-report
````

##### Add Service Provider in app.php

```
AnshulNetgen\JasperReport\JasperReportServiceProvider::class
```

##### Usage

###### Using Api:
```
<?php

use AnshulNetgen\JasperReport\Helpers\JasperReport;

JasperReport::make('pdf', 'http://localhost:8001/api/users');
```

###### Using JSON:
```
<?php

use AnshulNetgen\JasperReport\Helpers\JasperReport;

JasperReport::makeFromJson('pdf', $json);
```

[This Library is using https://github.com/PHPJasper/phpjasper]([https://](https://github.com/PHPJasper/phpjasper))
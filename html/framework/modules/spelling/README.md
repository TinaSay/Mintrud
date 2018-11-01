The module provides functionality by pressing the button CTRL + ENTER.

How use:
1. Open layout (maybe page or post)
2. Insert
```
use app\modules\spelling\widgets\SpellingWidget;
```
3. Insert widget
```
<?=SpellingWidget::widget();?>
```
or
```
<?=app\modules\spelling\widgets\SpellingWidget::widget();?>
```

In this project I inserted to ```@app\views\parts\footer.php 15 line```

File ```@app\config\params.php``` contains ```'emailError' => 'mintrud@nsign.ru',```
and use in ```@app\modules\spelling\controllers\frontend\DefaultController``` in 33 line

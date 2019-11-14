# RSS Feed Yii 2
Rss feed example built on Yii2

Prerequisites
  * composer
  * php 7.0 with SimpleXmlElement, DOM and mbstring extensions
  
1. Create basic Yii2 project
	* composer create-project --prefer-dist yiisoft/yii2-app-basic rss-feed
2. Overwrite project folders with folders from git archive
	* rss-feed/controllers
	* rss-feed/models 
	* rss-feed/views
3. Go to project folder /rss-feed and start local web server 
	* php yii serve --port=8888
4. In web browser open http://localhost:8888 There are 3 pages:
	* Login - http://localhost:8888/index.php?r=site%2Flogin
	* Register - http://localhost:8888/index.php?r=register
	* Feed - http://localhost:8888/

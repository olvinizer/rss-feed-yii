<?php


namespace app\controllers;

use SimpleXMLElement;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;

class FeedController extends Controller
{
    public $itemsPerPage = 5;
    public $common50 = ['the' => true, 'be' => true, 'to' => true, 'of' => true, 'and' => true, 'a' => true, 'in' => true, 'that' => true, 'have' => true, 'I' => true, 'it' => true, 'for' => true, 'not' => true, 'on' => true, 'with' => true, 'he' => true, 'as' => true, 'you' => true, 'do' => true, 'at' => true, 'this' => true, 'but' => true, 'his' => true, 'by' => true, 'from' => true, 'they' => true, 'we' => true, 'say' => true, 'her' => true, 'she' => true, 'or' => true, 'an' => true, 'will' => true, 'my' => true, 'one' => true, 'all' => true, 'would' => true, 'there' => true, 'their' => true, 'what' => true, 'so' => true, 'up' => true, 'out' => true, 'if' => true, 'about' => true, 'who' => true, 'get' => true, 'which' => true, 'go' => true, 'me' => true];

    /**
     * Controller is available only for authenticated users
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }

    public function actionIndex()
    {
        $feed = $this->getSource();
        $top = $this->getWordsTop($feed);
        $pagination = new Pagination(['totalCount' => count($feed->xpath('atom:entry')), 'defaultPageSize' => $this->itemsPerPage]);
        return $this->render('index', compact('feed', 'pagination', 'top'));
    }

    /**
     * Returns TOP X words in XML document, excluding TOP50 common english words
     * @param SimpleXMLElement $feed
     * @param int $count
     * @return 0|array
     */
    private function getWordsTop(SimpleXMLElement $feed, $count = 10)
    {
        $node = dom_import_simplexml($feed);
        $text = strip_tags($node->textContent); //retrieving text nodes and stripping html tags
        $words = str_word_count($text, 1); //split text into words
        $unique = [];
        //counting words with length 2+, excluding 50 common english words
        foreach ($words as $word) {
            if (mb_strlen($word) > 1 && !isset($this->common50[$word])) {
                $unique[$word] = isset($unique[$word]) ? $unique[$word] + 1 : 1;
            }
        }
        natsort($unique);
        return array_slice(array_reverse($unique), 0, $count);
    }

    private function getSource()
    {
        $xml = file_get_contents('https://www.theregister.co.uk/software/headlines.atom');
        $feed = new SimpleXmlElement($xml);
        $feed->registerXpathNamespace('atom', 'http://www.w3.org/2005/Atom');
        return $feed;
    }
}

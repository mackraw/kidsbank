<?php
// Controller to view the articles.

defined('BASEPATH') or exit('No direct script access allowed');

class Articles extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('articles_model');
    $this->load->helper('url_helper');
    $this->load->library('parser');
  }

  // View all articles
  public function index() {
    $headerdata['pagetitle'] = 'Articles - Kids\' Bank';

    $bodydata['title'] = 'Articles';
    $articles['articles'] = $this->articles_model->get_articles();
    foreach ($articles['articles'] as &$art) {
      if ($art['importance'] == '1') {
        $art['styles'] = array(array('style' => ' featured'));
      } else {
        $art['styles'] = array(array('style' => ''));
      }
    }
    $bodydata['articles'] = $this->parser->parse('templates/articles_template', $articles, TRUE);

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js')
      )
    );

    $this->load->view('templates/header', $headerdata);
    $this->load->view('pages/articles', $bodydata);
    $this->parser->parse('templates/footer', $footerdata);
  }

  // View single article
  public function view($slug = NULL) {
    $article = $this->articles_model->get_article($slug);

    if (empty($article)) {
      show_404();
    }

    $headerdata['pagetitle'] = $article['title'] . ' - Kids\' Bank';

    $bodydata['article'] = $this->parser->parse('templates/article_template', $article, TRUE);

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js')
      )
    );

    $this->load->view('templates/header', $headerdata);
    $this->load->view('pages/article', $bodydata);
    $this->parser->parse('templates/footer', $footerdata);
  }
}

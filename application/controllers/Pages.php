<?php
// Purpose: Controller to view pages.

defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller {

  public function view($page = 'home') {
    if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
      // Whoops, we don't have a page for that!
      show_404();
    }

    $headerdata['pagetitle'] = ucfirst($page) . ' - Kids\' Bank';

    $footerdata = array(
      'localtime' => date('Y'),
      'pagename' => 'Kid\'s Bank',
      'scripts' => array(
        array('script' => './../../../assets/js/jquery-3.6.0.min.js'),
        array('script' => './../../../assets/js/bootstrap.min.js')
      )
    );

    if ($page == 'register') {
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/main.js'));
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/register.js'));
    }

    if ($page == 'contact') {
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/main.js'));
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/contact.js'));
    }

    if ($page == 'locations') {
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/main.js'));
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/mustache.min.js'));
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/locations.js'));
    }

    if ($page == 'login') {
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/main.js'));
      array_push($footerdata['scripts'], array('script' => './../../../assets/js/login.js'));
    }

    $this->load->library('parser');
    $this->load->view('templates/header', $headerdata);
    $this->load->view('pages/' . $page);
    $this->parser->parse('templates/footer', $footerdata);
  }
}

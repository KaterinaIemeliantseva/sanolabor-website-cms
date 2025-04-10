<?php
class Template
{
  var $vsebina;
  var $nivo0;
  var $nivo1;
  var $nivo2;
  var $nivo3;
  var $nivo4;
  var $currentNivo;
  var $user;
  public $kategorijaNaziv;
  public $handler;


  function __construct($user)
  {
    $this->user = $user;
    $this->base = new BaseBAL();
    $this->DobiNicenameVrednost();
    $this->handler = new SuperClass();
  }

  function dobiKategorijaNaziv()
  {
      if(defined('NIVO1_NICENAME'))
      {
          $res =  $this->base->dobiKategorijaNaziv(NIVO1_NICENAME);
          return $res['naslov'];
      }

      return '';
  }

  function DobiNicenameVrednost()
  {
    $this->currentNivo = 'home';
    if(defined('NIVO0_NICENAME') && NIVO0_NICENAME != 'home')
    {
      $this->nivo0 = $this->base->getVsebinaByNicename(NIVO0_NICENAME);
      $this->currentNivo = NIVO0_NICENAME;
    }
    if(defined('NIVO1_NICENAME'))
    {
      $this->nivo1 = $this->base->getVsebinaByNicename(NIVO1_NICENAME);
      $this->currentNivo = NIVO1_NICENAME;
    }
    if(defined('NIVO2_NICENAME'))
    {
      $this->nivo2 = $this->base->getVsebinaByNicename(NIVO2_NICENAME);
      $this->currentNivo = NIVO2_NICENAME;
    }
    if(defined('NIVO3_NICENAME'))
    {
      $this->nivo3 = $this->base->getVsebinaByNicename(NIVO3_NICENAME);
      $this->currentNivo = NIVO3_NICENAME;
    }
    if(defined('NIVO4_NICENAME'))
    {
      $this->nivo4 = $this->base->getVsebinaByNicename(NIVO4_NICENAME);
      $this->currentNivo = NIVO4_NICENAME;
    }
  }




  function Render()
  {
    //header
    include (ROOT . DS . 'public' . DS . 'html' . DS . 'main' . DS . 'header.php');

    //content
    include (ROOT . DS . 'public' . DS . 'html' . DS . 'main' . DS . 'content.php');

    //footer
    include (ROOT . DS . 'public' . DS . 'html' . DS . 'main' . DS . 'footer.php');
  }

  function Controller()
  {
    $nicename = "";
    if(defined('NIVO0_NICENAME') && file_exists(ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . str_replace('-', '_', NIVO0_NICENAME).'.php')) $nicename = NIVO0_NICENAME;
    if(defined('NIVO1_NICENAME') && file_exists(ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . str_replace('-', '_', NIVO0_NICENAME) . DS . str_replace('-', '_', NIVO1_NICENAME).'.php')) $nicename = NIVO1_NICENAME;
    if(defined('NIVO2_NICENAME') && file_exists(ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . str_replace('-', '_', NIVO0_NICENAME) . DS . str_replace('-', '_', NIVO2_NICENAME).'.php')) $nicename = NIVO2_NICENAME;
    if(defined('NIVO3_NICENAME') && file_exists(ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . str_replace('-', '_', NIVO0_NICENAME) . DS . str_replace('-', '_', NIVO3_NICENAME).'.php')) $nicename = NIVO3_NICENAME;
    if(defined('NIVO4_NICENAME') && file_exists(ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . str_replace('-', '_', NIVO0_NICENAME) . DS . str_replace('-', '_', NIVO4_NICENAME).'.php')) $nicename = NIVO4_NICENAME;
    $nicename = str_replace('-', '_', $nicename);

    if (!empty($nicename))
    {
      if(defined('NIVO1_NICENAME')) include (ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . str_replace('-', '_', NIVO0_NICENAME) . DS . $nicename.'.php');
      else include (ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . $nicename.'.php');

    }
    else
    {
      include (ROOT . DS . 'public' . DS . 'html' . DS . 'controller' . DS . 'default.php');
    }
  }

}

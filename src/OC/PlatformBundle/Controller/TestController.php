<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TestController extends Controller
{
  public function indexAction()
  {
  
    return $this->render('OCPlatformBundle:Test:index.html.twig');
  }
}
  
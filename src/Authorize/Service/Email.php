<?php
namespace Authorize\Service;

use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\TemplatePathStack;

class Email extends ServiceLocatorAware
{
    protected $from = null;
    protected $to = null;
    protected $subject = null;
    protected $body = null;

    protected $renderer = null;
    protected $transport = null;

    public function send()
    {
        $this->initialise();

        $body = new MimeMessage();

        $htmlPart = new MimePart($this->body);
        $htmlPart->type = "text/html";

        $partArray = array($htmlPart);
        $body->setParts($partArray);

        $message = new Mail\Message();

        $message->setFrom($this->from);
        $message->addTo($this->to);
        $message->setSubject($this->subject);
        $message->setEncoding("UTF-8");
        $message->setBody($body);

        $transport = $this->getTransport();
        $transport->send($message);
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function body($template, $vars = array())
    {
        $viewModel = new ViewModel($vars);
        $viewModel->setTemplate('authorize/emails/' . $template);

        $this->body = $this->getRenderer()->render($viewModel);
        return $this;
    }

    public function initialise()
    {
        if (is_null($this->to) || is_null($this->from) || is_null($this->subject) || is_null($this->body)) {
            throw new \Exception('Please ensure TO, FROM, SUBJECT & BODY have been set before sending');
        }

        return true;
    }

    /**
     * Return the PHP Renderer to render the partials
     * @return null|PhpRenderer
     */
    protected function getRenderer()
    {
        if (! is_null($this->renderer)) {
            return $this->renderer;
        }

        $renderer = $this->getServiceLocator()->get('Zend\View\Renderer\RendererInterface');
        $resolver = new AggregateResolver();
        $stack = new TemplatePathStack();

        $config = $this->getServiceLocator()->get('Config');

        foreach($config['view_manager']["template_path_stack"] as $path) {
            $stack->addPath($path);
        }

        $resolver->attach($stack);
        $renderer->setResolver($resolver);

        return $this->renderer = $renderer;
    }

    protected function getTransport()
    {
        if (! is_null($this->transport)) {
            return $this->transport;
        }

        $config = $this->getServiceLocator()->get('Config');

        // Select default transport based on config options
        if (strtolower($config['mail']['transport']['default']) == 'smtp') {
            $transport = new \Zend\Mail\Transport\Smtp();
            $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['mail']['transport']['options']));
        } else {  // Default option
            $transport = new \Zend\Mail\Transport\Sendmail();
        }

        return $this->transport = $transport;
    }
}

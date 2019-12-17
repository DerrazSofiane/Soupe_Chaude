<?php 


class FlashMessages {
  private $messages = array();
  private $now = false;
  private static $instance = null;
  
  private function __construct() {
    // Save all messages
    $this->messages = $_SESSION['flash_messages'];
    
    // Reset all flash messages or create the session
    $_SESSION['flash_messages'] = array();
  }
  
  // Only allows one instance of the class
  public static function instance() {
    if( self::$instance === null )
      self::$instance = new FlashMessages;
    return self::$instance;
  }
  // don't allow cloning
  private function __clone() {}
  
  // Allows simple message adding
  // usage: flash()->notice('You have logged in successfully');
  public function __call($name, $args) {
    $message = $args[0];
    $this->message($name, $message);
  }
  
  public function message($name, $message) {
    if( $this->now ) {
      $this->messages[] = array(
        'name' => $name,
        'message' => $message
      );
      $this->now = false;
    }else
      $_SESSION['flash_messages'][] = array(
        'name' => $name,
        'message' => $message
      );
  }
  
  public function each($callback = null) {
    
    // Set default markup
    if( $callback === null ) {
      $callback = function($name, $message) {
        echo '<div class="flash_' . $name . '">' . $message . '</div>';
      };
    }
    
    foreach( $this->messages as $flash ) {
      echo $callback($flash['name'], $flash['message']);
    }
  }
  
  // Allows message to be displayed instantly
  // (opposed to waiting for next page request)
  public function now() {
    $this->now = true;
    return $this;
  }
  
}
// Allows shorthand
function flash() {
  return FlashMessages::instance();
}

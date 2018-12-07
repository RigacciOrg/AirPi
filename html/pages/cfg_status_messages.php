<?php
class StatusMessages {
    public $messages = array();

    public function addMessage($message, $level='success', $dismissable=TRUE) {
        $status = '<div class="alert alert-' . $level;
        if ($dismissable) $status .= ' alert-dismissable';
        $status .= '">' . my_html($message);
        if ($dismissable) $status .= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
        $status .= '</div>';

        array_push($this->messages, $status);
    }

    public function showMessages($clear=TRUE) {
        foreach($this->messages as $message) {
            echo $message . "\n";
        }
        if ($clear) $this->messages = array();
    }
}
?>

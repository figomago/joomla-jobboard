<?php
/**
  @package JobBoard
  @copyright Copyright (c)2010-2013 Figomago <http://figomago.wordpress.com>
  @license : GNU General Public License v3 or later
----------------------------------------------------------------------- */

    defined('_JEXEC') or die('Restricted access');
?>
<?php echo 'name: '.$this->first_name.'<br />file: '.$this->filename; ?>
<br />
<?php echo $this->result; ?>
<br />
<?php echo json_encode($this->post); ?>
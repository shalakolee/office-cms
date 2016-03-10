<?php


/*
 * available error types:
 * success
 * info
 * warning
 * danger
 *
 */
class messageReport{
	var $reports;
	
	function addMessage($message,$message_type){
		$this->reports[ $message_type ][] = $message;
	}

	function reportMessage($message,$message_type){
		if ( $message != '' && $message_type != '' ){	
			$this->addMessage($message,$message_type);
		}

		$keys = array_keys($this->reports);
		$count_i = count($this->reports) - 1;
		for ( $i = 0; $i <= $count_i; $i++ ){
			$key = $keys[$i];
			if ( is_array($this->reports[$key]) ){
			?>
				<div class="alert alert-<?=$key?> alert-dismissible" role='alert' >
	              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p>
					<?php
					$count_j = count($this->reports[$key]) - 1;
					for ( $j = 0; $j <= $count_j; $j++ ){ 
						echo $this->reports[$key][$j].'<br />';
					}
					?>
					</p>
				</div>
			<?php
			}
		}

		$this->clearMessages();
	}
	// clear message queue
	function clearMessages(){
		unset($this->reports);
	}
}
?>
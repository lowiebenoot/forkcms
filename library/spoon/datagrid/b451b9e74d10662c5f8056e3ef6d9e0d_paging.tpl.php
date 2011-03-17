<?php error_reporting(E_ALL | E_STRICT); ini_set('display_errors', 'On'); ?>

<?php
					if(isset($this->variables['previousURL']) && count($this->variables['previousURL']) != 0 && $this->variables['previousURL'] != '' && $this->variables['previousURL'] !== false)
					{
						?><a href="<?php if(array_key_exists('previousURL', (array) $this->variables)) { echo $this->variables['previousURL']; } else { ?>{$previousURL}<?php } ?>" title="<?php if(array_key_exists('previousLabel', (array) $this->variables)) { echo $this->variables['previousLabel']; } else { ?>{$previousLabel}<?php } ?>"><?php } ?>
	&laquo; previous
<?php
					if(isset($this->variables['previousURL']) && count($this->variables['previousURL']) != 0 && $this->variables['previousURL'] != '' && $this->variables['previousURL'] !== false)
					{
						?></a><?php } ?>


<?php
					if(!isset($this->variables['pages']))
					{
						?>{iteration:pages}<?php
						$this->variables['pages'] = array();
						$this->iterations[1]['fail'] = true;
					}
				if(isset($this->iterations[1])) $this->iterations[1]['old'] = $this->iterations[1];
				$this->iterations[1]['iteration'] = $this->variables['pages'];
				$this->iterations[1]['i'] = 1;
				$this->iterations[1]['count'] = count($this->iterations[1]['iteration']);
				foreach((array) $this->iterations[1]['iteration'] as ${'pages'})
				{
					if(!isset(${'pages'}['first']) && $this->iterations[1]['i'] == 1) ${'pages'}['first'] = true;
					if(!isset(${'pages'}['last']) && $this->iterations[1]['i'] == $this->iterations[1]['count']) ${'pages'}['last'] = true;
					if(isset(${'pages'}['formElements']) && is_array(${'pages'}['formElements']))
					{
						foreach(${'pages'}['formElements'] as $name => $object)
						{
							${'pages'}[$name] = $object->parse();
							${'pages'}[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
						}
					} ?>
	<?php
					if(isset(${'pages'}['page']) && count(${'pages'}['page']) != 0 && ${'pages'}['page'] != '' && ${'pages'}['page'] !== false)
					{
						?>
		<?php
					if(isset(${'pages'}['currentPage']) && count(${'pages'}['currentPage']) != 0 && ${'pages'}['currentPage'] != '' && ${'pages'}['currentPage'] !== false)
					{
						?><strong><?php if(array_key_exists('pageNumber', (array) ${'pages'})) { echo ${'pages'}['pageNumber']; } else { ?>{$pages->pageNumber}<?php } ?></strong><?php } ?>
		<?php
					if(isset(${'pages'}['otherPage']) && count(${'pages'}['otherPage']) != 0 && ${'pages'}['otherPage'] != '' && ${'pages'}['otherPage'] !== false)
					{
						?><a href="<?php if(array_key_exists('url', (array) ${'pages'})) { echo ${'pages'}['url']; } else { ?>{$pages->url}<?php } ?>"><?php if(array_key_exists('pageNumber', (array) ${'pages'})) { echo ${'pages'}['pageNumber']; } else { ?>{$pages->pageNumber}<?php } ?></a><?php } ?>
	<?php } ?>

	<?php
					if(isset(${'pages'}['noPage']) && count(${'pages'}['noPage']) != 0 && ${'pages'}['noPage'] != '' && ${'pages'}['noPage'] !== false)
					{
						?>&hellip;<?php } ?>
<?php
					$this->iterations[1]['i']++;
				}
					if(isset($this->iterations[1]['fail']) && $this->iterations[1]['fail'] == true)
					{
						?>{/iteration:pages}<?php
					}
					if(isset($this->iterations[1]['old'])) $this->iterations[1] = $this->iterations[1]['old'];
					else unset($this->iterations[1]);?>


<?php
					if(isset($this->variables['nextURL']) && count($this->variables['nextURL']) != 0 && $this->variables['nextURL'] != '' && $this->variables['nextURL'] !== false)
					{
						?><a href="<?php if(array_key_exists('nextURL', (array) $this->variables)) { echo $this->variables['nextURL']; } else { ?>{$nextURL}<?php } ?>" title="<?php if(array_key_exists('nextLabel', (array) $this->variables)) { echo $this->variables['nextLabel']; } else { ?>{$nextLabel}<?php } ?>"><?php } ?>
	 next &raquo;
<?php
					if(isset($this->variables['nextURL']) && count($this->variables['nextURL']) != 0 && $this->variables['nextURL'] != '' && $this->variables['nextURL'] !== false)
					{
						?></a><?php } ?>
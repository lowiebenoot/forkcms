<?php error_reporting(E_ALL | E_STRICT); ini_set('display_errors', 'On'); ?>


<table<?php
					if(isset($this->variables['summary']) && count($this->variables['summary']) != 0 && $this->variables['summary'] != '' && $this->variables['summary'] !== false)
					{
						?> summary="<?php if(array_key_exists('summary', (array) $this->variables)) { echo $this->variables['summary']; } else { ?>{$summary}<?php } ?>"<?php } ?><?php if(array_key_exists('attributes', (array) $this->variables)) { echo $this->variables['attributes']; } else { ?>{$attributes}<?php } ?>>
	<?php
					if(isset($this->variables['caption']) && count($this->variables['caption']) != 0 && $this->variables['caption'] != '' && $this->variables['caption'] !== false)
					{
						?><caption><?php if(array_key_exists('caption', (array) $this->variables)) { echo $this->variables['caption']; } else { ?>{$caption}<?php } ?></caption><?php } ?>
	<thead>
		<tr>
			<?php
					if(!isset($this->variables['headers']))
					{
						?>{iteration:headers}<?php
						$this->variables['headers'] = array();
						$this->iterations[1]['fail'] = true;
					}
				if(isset($this->iterations[1])) $this->iterations[1]['old'] = $this->iterations[1];
				$this->iterations[1]['iteration'] = $this->variables['headers'];
				$this->iterations[1]['i'] = 1;
				$this->iterations[1]['count'] = count($this->iterations[1]['iteration']);
				foreach((array) $this->iterations[1]['iteration'] as ${'headers'})
				{
					if(!isset(${'headers'}['first']) && $this->iterations[1]['i'] == 1) ${'headers'}['first'] = true;
					if(!isset(${'headers'}['last']) && $this->iterations[1]['i'] == $this->iterations[1]['count']) ${'headers'}['last'] = true;
					if(isset(${'headers'}['formElements']) && is_array(${'headers'}['formElements']))
					{
						foreach(${'headers'}['formElements'] as $name => $object)
						{
							${'headers'}[$name] = $object->parse();
							${'headers'}[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
						}
					} ?>
				<th<?php if(array_key_exists('attributes', (array) ${'headers'})) { echo ${'headers'}['attributes']; } else { ?>{$headers->attributes}<?php } ?>>
					<?php
					if(isset(${'headers'}['sorting']) && count(${'headers'}['sorting']) != 0 && ${'headers'}['sorting'] != '' && ${'headers'}['sorting'] !== false)
					{
						?>
						<?php
					if(isset(${'headers'}['sorted']) && count(${'headers'}['sorted']) != 0 && ${'headers'}['sorted'] != '' && ${'headers'}['sorted'] !== false)
					{
						?>
							<a href="<?php if(array_key_exists('sortingURL', (array) ${'headers'})) { echo ${'headers'}['sortingURL']; } else { ?>{$headers->sortingURL}<?php } ?>" title="<?php if(array_key_exists('sortingLabel', (array) ${'headers'})) { echo ${'headers'}['sortingLabel']; } else { ?>{$headers->sortingLabel}<?php } ?>"><?php if(array_key_exists('label', (array) ${'headers'})) { echo ${'headers'}['label']; } else { ?>{$headers->label}<?php } ?><?php
					if(isset(${'headers'}['sortingIcon']) && count(${'headers'}['sortingIcon']) != 0 && ${'headers'}['sortingIcon'] != '' && ${'headers'}['sortingIcon'] !== false)
					{
						?> <img src="<?php if(array_key_exists('sortingIcon', (array) ${'headers'})) { echo ${'headers'}['sortingIcon']; } else { ?>{$headers->sortingIcon}<?php } ?>" /><?php } ?></a>
						<?php } ?>
						<?php
					if(isset(${'headers'}['notSorted']) && count(${'headers'}['notSorted']) != 0 && ${'headers'}['notSorted'] != '' && ${'headers'}['notSorted'] !== false)
					{
						?>
							<a href="<?php if(array_key_exists('sortingURL', (array) ${'headers'})) { echo ${'headers'}['sortingURL']; } else { ?>{$headers->sortingURL}<?php } ?>" title="<?php if(array_key_exists('sortingLabel', (array) ${'headers'})) { echo ${'headers'}['sortingLabel']; } else { ?>{$headers->sortingLabel}<?php } ?>"><?php if(array_key_exists('label', (array) ${'headers'})) { echo ${'headers'}['label']; } else { ?>{$headers->label}<?php } ?><?php
					if(isset(${'headers'}['sortingIcon']) && count(${'headers'}['sortingIcon']) != 0 && ${'headers'}['sortingIcon'] != '' && ${'headers'}['sortingIcon'] !== false)
					{
						?> <img src="<?php if(array_key_exists('sortingIcon', (array) ${'headers'})) { echo ${'headers'}['sortingIcon']; } else { ?>{$headers->sortingIcon}<?php } ?>" /><?php } ?></a>
						<?php } ?>
					<?php } ?>

					<?php
					if(isset(${'headers'}['noSorting']) && count(${'headers'}['noSorting']) != 0 && ${'headers'}['noSorting'] != '' && ${'headers'}['noSorting'] !== false)
					{
						?>
						<?php if(array_key_exists('label', (array) ${'headers'})) { echo ${'headers'}['label']; } else { ?>{$headers->label}<?php } ?>
					<?php } ?>
				</th>
			<?php
					$this->iterations[1]['i']++;
				}
					if(isset($this->iterations[1]['fail']) && $this->iterations[1]['fail'] == true)
					{
						?>{/iteration:headers}<?php
					}
					if(isset($this->iterations[1]['old'])) $this->iterations[1] = $this->iterations[1]['old'];
					else unset($this->iterations[1]);?>
		</tr>
	</thead>
	<tbody>
		<?php
					if(!isset($this->variables['rows']))
					{
						?>{iteration:rows}<?php
						$this->variables['rows'] = array();
						$this->iterations[2]['fail'] = true;
					}
				if(isset($this->iterations[2])) $this->iterations[2]['old'] = $this->iterations[2];
				$this->iterations[2]['iteration'] = $this->variables['rows'];
				$this->iterations[2]['i'] = 1;
				$this->iterations[2]['count'] = count($this->iterations[2]['iteration']);
				foreach((array) $this->iterations[2]['iteration'] as ${'rows'})
				{
					if(!isset(${'rows'}['first']) && $this->iterations[2]['i'] == 1) ${'rows'}['first'] = true;
					if(!isset(${'rows'}['last']) && $this->iterations[2]['i'] == $this->iterations[2]['count']) ${'rows'}['last'] = true;
					if(isset(${'rows'}['formElements']) && is_array(${'rows'}['formElements']))
					{
						foreach(${'rows'}['formElements'] as $name => $object)
						{
							${'rows'}[$name] = $object->parse();
							${'rows'}[$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
						}
					} ?>
			<tr<?php if(array_key_exists('attributes', (array) ${'rows'})) { echo ${'rows'}['attributes']; } else { ?>{$rows->attributes}<?php } ?><?php if(array_key_exists('oddAttributes', (array) ${'rows'})) { echo ${'rows'}['oddAttributes']; } else { ?>{$rows->oddAttributes}<?php } ?><?php if(array_key_exists('evenAttributes', (array) ${'rows'})) { echo ${'rows'}['evenAttributes']; } else { ?>{$rows->evenAttributes}<?php } ?>>
				<?php
					if(!isset(${'rows'}['columns']))
					{
						?>{iteration:rows->columns}<?php
						${'rows'}['columns'] = array();
						$this->iterations[3]['columns']['fail'] = true;
					}
				if(isset($this->iterations[3]['columns'])) $this->iterations[3]['columns']['old'] = $this->iterations[3]['columns'];
				$this->iterations[3]['columns']['iteration'] = ${'rows'}['columns'];
				$this->iterations[3]['columns']['i'] = 1;
				$this->iterations[3]['columns']['count'] = count($this->iterations[3]['columns']['iteration']);
				foreach((array) $this->iterations[3]['columns']['iteration'] as ${'rows'}['columns'])
				{
					if(!isset(${'rows'}['columns']['first']) && $this->iterations[3]['columns']['i'] == 1) ${'rows'}['columns']['first'] = true;
					if(!isset(${'rows'}['columns']['last']) && $this->iterations[3]['columns']['i'] == $this->iterations[3]['columns']['count']) ${'rows'}['columns']['last'] = true;
					if(isset(${'rows'}['columns']['formElements']) && is_array(${'rows'}['columns']['formElements']))
					{
						foreach(${'rows'}['columns']['formElements'] as $name => $object)
						{
							${'rows'}['columns'][$name] = $object->parse();
							${'rows'}['columns'][$name .'Error'] = (method_exists($object, 'getErrors') && $object->getErrors() != '') ? '<span class="formError">'. $object->getErrors() .'</span>' : '';
						}
					} ?><td<?php if(isset(${'rows'}['columns']) && array_key_exists('attributes', (array) ${'rows'}['columns'])) { echo ${'rows'}['columns']['attributes']; } else { ?>{$rows->columns.attributes}<?php } ?>><?php if(isset(${'rows'}['columns']) && array_key_exists('value', (array) ${'rows'}['columns'])) { echo ${'rows'}['columns']['value']; } else { ?>{$rows->columns.value}<?php } ?></td><?php
					$this->iterations[3]['columns']['i']++;
				}
					if(isset($this->iterations[3]['columns']['fail']) && $this->iterations[3]['columns']['fail'] == true)
					{
						?>{/iteration:rows->columns}<?php
					}
					if(isset($this->iterations[3]['columns']['old'])) $this->iterations[3]['columns'] = $this->iterations[3]['columns']['old'];
					else unset($this->iterations[3]['columns']);?>
			</tr>
		<?php
					$this->iterations[2]['i']++;
				}
					if(isset($this->iterations[2]['fail']) && $this->iterations[2]['fail'] == true)
					{
						?>{/iteration:rows}<?php
					}
					if(isset($this->iterations[2]['old'])) $this->iterations[2] = $this->iterations[2]['old'];
					else unset($this->iterations[2]);?>
	</tbody>
	<?php
					if(isset($this->variables['paging']) && count($this->variables['paging']) != 0 && $this->variables['paging'] != '' && $this->variables['paging'] !== false)
					{
						?>
		<tfoot>
			<tr<?php if(array_key_exists('footerAttributes', (array) $this->variables)) { echo $this->variables['footerAttributes']; } else { ?>{$footerAttributes}<?php } ?>>
				<td colspan="<?php if(array_key_exists('numColumns', (array) $this->variables)) { echo $this->variables['numColumns']; } else { ?>{$numColumns}<?php } ?>"><?php if(array_key_exists('paging', (array) $this->variables)) { echo $this->variables['paging']; } else { ?>{$paging}<?php } ?></td>
			</tr>
		</tfoot>
	<?php } ?>
</table>
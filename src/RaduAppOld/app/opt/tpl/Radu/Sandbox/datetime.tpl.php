<article>
	<section class="box_border">
		<h2><?php echo $title; ?></h2>
		
		<form class="datetime" enctype="multipart/form-data" method="post" action="">
		
			<fieldset>
			
			
				<legend>Calculate starting time based on arrival time and duration</legend>
				
				<label class="tooltip" for="duration" title="Duration">
					Duration:
				</label>
				
				<p>
					<select name="cst_duration_h">
						<?php for ($h=0;$h<24;$h++) { 
							echo "<option value=\"{$h}\"";
							if ($h == $cst['duration_h']) {
								echo ' selected="selected"';
							}
							echo ">{$h}</option>"; 
						} ?>
					</select>
					<select name="cst_duration_m">
						<?php for ($m=0;$m<60;$m++) { 
							echo "<option value=\"{$m}\"";
							if ($m == $cst['duration_m']) {
								echo ' selected="selected"';
							}
							echo ">{$m}</option>";
						} ?>
					</select>
					<select name="cst_duration_s">
						<?php for ($s=0;$s<60;$s++) { 
							echo "<option value=\"{$s}\"";
							if ($s == $cst['duration_s']) {
								echo ' selected="selected"';
							}
							echo ">{$s}</option>";
						} ?>
					</select>
				</p>
				
				<label class="tooltip" for="cst_endtime" title="End time">
					Arrival time:
				</label>
				
				<p>
					<select name="cst_endtime_h">
						<?php for ($h=0;$h<24;$h++) { 
							echo "<option value=\"{$h}\"";
							if ($h == $cst['endtime_h']) {
								echo ' selected="selected"';
							}
							echo ">{$h}</option>";
						} ?>
					</select>
					<select name="cst_endtime_m">
						<?php for ($m=0;$m<60;$m++) { 
							echo "<option value=\"{$m}\"";
							if ($m == $cst['endtime_m']) {
								echo ' selected="selected"';
							}
							echo ">{$m}</option>";
						} ?>
					</select>
					<select name="cst_endtime_s">
						<?php for ($s=0;$s<60;$s++) { 
							echo "<option value=\"{$s}\"";
							if ($s == $cst['endtime_s']) {
								echo ' selected="selected"';
							}
							echo ">{$s}</option>";
						} ?>
					</select>
				</p>
				
				<?php /*
				<label class="tooltip" for="cst_offset" title="Offset">
					Time offset:
				</label>
				
				<p>
					<select name="cst_offset">
						<?php for ($h=-24;$h<24;$h++) { 
							echo "<option value=\"{$h}\"";
							if ($h == $cst['offset']) {
								echo ' selected="selected"';
							}
							echo ">{$h}</option>";
						} ?>
					</select>
					<span class="datetime_info">Hours between GMT time and calculated time</span>
				</p>
				*/?>
				
				<label class="tooltip" for="send" title="">
					&#160;
				</label>
					
				<p>
					<input class="tooltip" title="" type="submit" name="cst" id="cst" value="Calculate" />
					&#160;&#160;&#160;
					<a class="submit" href="<?php echo $url_app; ?>datetime.html" title="Reset">Reset</a> 
				</p>
				
				
				<label class="tooltip" for="" title="">
					&#160;
				</label>
				<p>
					Start time is <?php echo ('00:00:00' != $cst['starttime']) ? "<strong class=\"\">{$cst['starttime']}</strong>" : 'empty'; ?>				
				</p>
					
			</fieldset>
		
		</form>
		
		<form class="datetime" enctype="multipart/form-data" method="post" action="">
		
			<fieldset>
			
			
				<legend>Calculate arrival time based on starting time and duration</legend>
				
				<label class="tooltip" for="cet_starttime" title="Start time">
					Start time:
				</label>
				
				<p>
					<select name="cet_starttime_h">
						<?php for ($h=0;$h<24;$h++) { 
							echo "<option value=\"{$h}\"";
							if ($h == $cet['starttime_h']) {
								echo ' selected="selected"';
							}
							echo ">{$h}</option>";
						} ?>
					</select>
					<select name="cet_starttime_m">
						<?php for ($m=0;$m<60;$m++) { 
							echo "<option value=\"{$m}\"";
							if ($m == $cet['starttime_m']) {
								echo ' selected="selected"';
							}
							echo ">{$m}</option>";
						} ?>
					</select>
					<select name="cet_starttime_s">
						<?php for ($s=0;$s<60;$s++) { 
							echo "<option value=\"{$s}\"";
							if ($s == $cet['starttime_s']) {
								echo ' selected="selected"';
							}
							echo ">{$s}</option>";
						} ?>
					</select>
				</p>
				
				<label class="tooltip" for="duration" title="Duration">
					Duration:
				</label>
				
				<p>
					<select name="cet_duration_h">
						<?php for ($h=0;$h<24;$h++) { 
							echo "<option value=\"{$h}\"";
							if ($h == $cet['duration_h']) {
								echo ' selected="selected"';
							}
							echo ">{$h}</option>"; 
						} ?>
					</select>
					<select name="cet_duration_m">
						<?php for ($m=0;$m<60;$m++) { 
							echo "<option value=\"{$m}\"";
							if ($m == $cet['duration_m']) {
								echo ' selected="selected"';
							}
							echo ">{$m}</option>";
						} ?>
					</select>
					<select name="cet_duration_s">
						<?php for ($s=0;$s<60;$s++) { 
							echo "<option value=\"{$s}\"";
							if ($s == $cet['duration_s']) {
								echo ' selected="selected"';
							}
							echo ">{$s}</option>";
						} ?>
					</select>
				</p>
				
				
				
				<?php /*
				<label class="tooltip" for="cet_offset" title="Offset">
					Time offset:
				</label>
				
				<p>
					<select name="cet_offset">
						<?php for ($h=-24;$h<24;$h++) { 
							echo "<option value=\"{$h}\"";
							if ($h == $cet['offset']) {
								echo ' selected="selected"';
							}
							echo ">{$h}</option>";
						} ?>
					</select>
					<span class="datetime_info">Hours between GMT time and calculated time</span>
				</p>
				*/?>
				
				<label class="tooltip" for="send" title="">
					&#160;
				</label>
					
				<p>
					<input class="tooltip" title="" type="submit" name="cet" id="cet" value="Calculate" />
					&#160;&#160;&#160;
					<a class="submit" href="<?php echo $url_app; ?>datetime.html" title="Reset">Reset</a> 
				</p>
				
				
				<label class="tooltip" for="" title="">
					&#160;
				</label>
				<p>
					Arrival time is 
					<?php
					if ('00:00:00' != $cet['endtime']) {
						
						/*
						if (1 == $cet['offset']) {
							if ('02:00:00' != $cet['endtime']) {
								echo "<strong class=\"\">{$cet['endtime']}</strong>";
							} else {
								echo 'empty';
							}
						} else {
						*/
							echo "<strong class=\"\">{$cet['endtime']}</strong>";
						/*	
						}
						*/
						
						
					}  else {
						echo 'empty';
					};
					?>				
				</p>
					
			</fieldset>
		
		</form>
	</section>
	
	<section class="box_border back">
		
		<a href="<?php echo $url_app; ?>sandbox.html" title="Sandbox">
			Back
		</a>
		
	</section>
</article>
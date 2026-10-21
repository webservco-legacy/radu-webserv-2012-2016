<section style="padding: 1em">

<strong>Trade Reply System</strong>
<br />
<br />


<?php if (!empty($form['result'])) { ?>
THANK YOU!
<?php } else { ?>

<form enctype="multipart/form-data" method="post" action="" autocomplete="off">
<fieldset>
    <legend>Dear</legend>
    <label><input type="radio" name="greet" value="Sir"<?php if ('Sir' == $form['data']['greet']) echo ' checked="checked"'?> /> Sir</label>
    <label><input type="radio" name="greet" value="Dude"<?php if ('Dude' == $form['data']['greet']) echo ' checked="checked"'?> /> Dude</label>
    <?php if (!empty($form['errors']['greet'])) echo "<strong style=\"color: #900\">{$form['errors']['greet']}</strong>";?>
</fieldset>

<br />

<fieldset>
    <legend>I will</legend>
    <label><input type="radio" name="pakken" value="not"<?php if ('not' == $form['data']['pakken']) echo ' checked="checked"'?> /> not</label>
    <label><input type="radio" name="pakken" value="surely"<?php if ('surely' == $form['data']['pakken']) echo ' checked="checked"'?> /> surely</label>
    <?php if (!empty($form['errors']['pakken'])) echo "<strong style=\"color: #900\">{$form['errors']['pakken']}</strong>";?>
</fieldset>

<fieldset>
     <legend>be sending your pakken this</legend>
    <label><input type="radio" name="period" value="week"<?php if ('week' == $form['data']['period']) echo ' checked="checked"'?> /> week</label>
    <label><input type="radio" name="period" value="month"<?php if ('month' == $form['data']['period']) echo ' checked="checked"'?> /> month</label>
    <label><input type="radio" name="period" value="year"<?php if ('year' == $form['data']['period']) echo ' checked="checked"'?> /> year</label>
    <?php if (!empty($form['errors']['period'])) echo "<strong style=\"color: #900\">{$form['errors']['period']}</strong>";?>
    <br />
    as agreed.
    
</fieldset>

<br />

<fieldset>
    <legend>Optional reason(s) for delay:</legend>
    <label><input type="checkbox" name="reason[1]" value="no time"<?php if (!empty($form['data']['reason'][1])) echo ' checked="checked"'?> /> no time</label>
    <label><input type="checkbox" name="reason[2]" value="no money"<?php if (!empty($form['data']['reason'][2])) echo ' checked="checked"'?> /> no money</label>
    <label><input type="checkbox" name="reason[3]" value="you already sent your part so why bother"<?php if (!empty($form['data']['reason'][3])) echo ' checked="checked"'?> /> you already sent your part so why bother</label>
</fieldset>

<br />

<fieldset>
    <label><input type="checkbox" name="sorry"<?php if (!empty($form['data']['sorry'])) echo ' checked="checked"'?> /> Sorry</label>
</fieldset>

<br />

<fieldset>
    <label>Signed: <input type="text" name="signed" value="<?=$form['data']['signed']?>" /> </label>
    <?php if (!empty($form['errors']['signed'])) echo "<strong style=\"color: #900\">{$form['errors']['signed']}</strong>";?>
</fieldset>

<br />

<fieldset>
    <label><input type="submit" name="reply" value="Reply" /></label>
</fieldset>

<br >
<?php /*if (!empty($form['errors'])) {
    foreach ($form['errors'] as $item) {
    ?> 
    <strong style="color: #900"><?=$item?></strong><br >
<?php
    }
} */?>

</form>


<?php } ?>
</section>
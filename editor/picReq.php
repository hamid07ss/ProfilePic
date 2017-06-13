<?php
/**
 * Created by PhpStorm.
 * User: M.Azadi <mohammad.azadi@yahoo.com>
 * Date: 1/30/2017
 * Time: 2:59 PM
 */






?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>upload pic</title>
    <link rel="stylesheet" href="styles/style.php"/>
	<link rel="stylesheet" href="styles/fonts.css" />
	<link rel="stylesheet" href="styles/bootstrap.css" />
	<link rel="stylesheet" href="styles/style.css"  />

	<script type="text/javascript" src="scripts/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="scripts/html2canvas.js"></script>
	<script type="text/javascript" src="color/picker.js"></script>
	<script src="tinymce/tinymce.min.js"></script>

	<script type="text/javascript" src="color/index.js"></script>
	<script type="text/javascript" src="scripts/script.js"></script>


</head>
<body>

<div class="pic-cont">
	<?php echo file_get_contents( '../FinalImages/' . $_GET["file"] . '.txt' )  ?>
</div>

<div style="text-align: center">
    <button class="btn btn-primary btn-send-tel" onclick="sendToTel(<?= $_GET["chat_id"] ?>, <?= $_GET["id"] ?>);">ارسال عکس به کاربر</button>
</div>

<div class="editor">
	<input type="submit" value="ذخیره این اسم" class="save-this-and-new btn btn-primary"/>
	<button type="submit" class="enable-move btn btn-primary">فعال کردن قابلیت حرکت</button>
	<div class="options">
		<div class="width">
			<label>طول</label>
			<div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="width">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
				<input type="number" title="picture width" name="width" class="form-control input-number picture-width" value="">
				<span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="width">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
			</div>
		</div>

		<div class="height">
			<label>عرض</label>
			<div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="width">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
				<input name="height" title="picture height" type="number" class="picture-height form-control input-number" />
				<span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="width">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
			</div>
		</div>

		<div class="rotate">
			<label>چرخش</label>
			<div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="width">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
				<input name="rotate" title="picture rotate" type="number" class="picture-rotate form-control input-number" />
				<span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="width">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
			</div>
		</div>

		<div class="top">
			<label>فاصله از بالا</label>
			<div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="width">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
				<input name="top" title="picture rotate" type="number" class="picture-top form-control input-number" />
				<span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="width">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
			</div>
		</div>

		<div class="left">
			<label>فاصله از چپ</label>
			<div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="width">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
				<input name="left" title="picture rotate" type="number" class="picture-left form-control input-number" />
				<span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="width">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
			</div>
		</div>

		<div class="font-size">
			<label>سایز متن</label>
			<div class="input-group">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="width">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </span>
				<input name="font-size" title="picture size" type="number" class="picture-font-size form-control input-number" />
				<span class="input-group-btn">
                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="width">
                        <span class="glyphicon glyphicon-plus"></span>
                    </button>
                </span>
			</div>
		</div>

		<div class="text-align">
			<label>مکان متن</label>
			<div class="input-group">
				<select name="text-align" title="picture text-align" class="picture-text-align form-control" >
					<option>راست</option>
					<option>وسط</option>
					<option>چپ</option>
				</select>
			</div>
		</div>
	</div>

	<div class="other-opt">

		<!--<div class="text" style="display: inline-block;">-->
		<!--<label>text</label>-->
		<!--<input name="text" title="picture text" type="text" class="picture-text form-control" />-->
		<!--</div>-->

		<div id="parent" style="display: inline-block;">رنگ متن</div>
	</div>

</div>

<div id="tiny-TextEditor" style="display: inline-block;">
	<textarea title="tiny-TextEditor" name="" id="TextEditor" cols="" rows=""></textarea>
</div>

<div style="margin: 20px">
	<input type="text" style="display: inline-block;width: 200px;" class="form-control fileName" />
	<button class="btn btn-primary" onclick="saveImage();">ذخیره عکس</button>
</div>

<div class="show-images">

</div>

<br/>
<br/>
<br/>

<form class="get-pic">
	<input type="file" name="picture" />

	<input type="button" id="getPhoto" value="submit"/>
</form>

<script>
    $(window).load(function () {
        var arr = <?= $_GET["names"] ?>;
        setNames( arr );
    });
</script>

</body>
</html>

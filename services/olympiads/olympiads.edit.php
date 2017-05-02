<?php
	DEFINED("SPM_GENUINE") OR DIE('403 ACCESS DENIED');
	deniedOrAllowed(PERMISSION::olymp);
	
	SPM_header("Олимпиадный режим", "Редактирование олимпиады");
?>

<?php _spm_view_msg("<b>ВНИМАНИЕ!</b> Данный функционал является экспериментальным! Стабильность данного функционала не гарантируется!", "danger"); ?>

<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Базовая информация</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0;">
				<thead>
					<th width="30%">Название поля</th>
					<th width="70%">Значение поля</th>
				</thead>
				<tbody>
					<tr>
						<td>ID</td>
						<td><input type="text" class="form-control" value="AUTO_INCREMENT"></td>
					</tr>
					<tr>
						<td>Название олимпиады</td>
						<td><input type="text" class="form-control"></td>
					</tr>
					<tr>
						<td>Описание олимпиады</td>
						<td><textarea class="form-control" style="resize: none;" rows="5"></textarea></td>
					</tr>
					<tr>
						<td>Дата и время начала олимпиады</td>
						<td>
							<input type="text" class="form-control" placeholder="ГГГГ-ММ-ДД ЧЧ:ММ:СС">
						</td>
					</tr>
					<tr>
						<td>Дата и время окончания олимпиады</td>
						<td>
							<input type="text" class="form-control" placeholder="ГГГГ-ММ-ДД ЧЧ:ММ:СС">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>


<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Включённые задачи</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0;">
				<thead>
					<th width="30%">Название поля</th>
					<th width="70%">Значение поля</th>
				</thead>
				<tbody>
					<tr>
						<td>Добавление задач по номерам<br/>(1 строка - 1 номер)</td>
						<td><textarea class="form-control" style="resize: none;" rows="5"></textarea></td>
					</tr>
					<tr>
						<td>Добавление задач по категориям<br/>(выбрать необходимые)</td>
						<td>
							<select class="form-control" size="5" multiple>
								<option selected>НЕТ</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Участники олимпиады</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0;">
				<thead>
					<th width="30%">Название поля</th>
					<th width="70%">Значение поля</th>
				</thead>
				<tbody>
					<tr>
						<td>Добавление участников по идентификаторам<br/>(1 строка - 1 номер)</td>
						<td><textarea class="form-control" style="resize: none;" rows="5"></textarea></td>
					</tr>
					<tr>
						<td>Добавление участников по группам<br/>(выбрать необходимые)</td>
						<td>
							<select class="form-control" size="5" multiple>
								<option selected>НЕТ</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
							</select>
						</td>
					</tr>
					<tr>
						<td>Добавление участников по учителям<br/>(выбрать необходимые)</td>
						<td>
							<select class="form-control" size="5" multiple>
								<option selected>НЕТ</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
								<option>Test</option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="panel panel-primary" style="border-radius: 0;">
	<div class="panel-heading" style="border-radius: 0;">
		<h3 class="panel-title">Параметры проведения</h3>
	</div>
	<div class="panel-body" style="padding: 0;">
		
		<div class="table-responsive" style="border-radius: 0; margin: 0;">
			<table class="table table-bordered" style="margin: 0;">
				<thead>
					<th width="30%">Название поля</th>
					<th width="70%">Значение поля</th>
				</thead>
				<tbody>
					<tr>
						<td>Разрешить частичное решение задачи участником олимпиады</td>
						<td><input type="checkbox" checked></td>
					</tr>
					<tr>
						<td>Отключить подсветку синтаксиса в олимпиадном режиме</td>
						<td><input type="checkbox"></td>
					</tr>
					<tr>
						<td>Блокировать участникам олимпиады доступ к сайту за 10 минут до начала олимпиады</td>
						<td><input type="checkbox" checked></td>
					</tr>
					<tr>
						<td>Запретить участникам олимпиады использовать мобильную версию SimplePM</td>
						<td><input type="checkbox" disabled></td>
					</tr>
					<tr>
						<td>Показывать рейтинг участников</td>
						<td><input type="checkbox" checked></td>
					</tr>
					<tr>
						<td>Показывать участникам олимпиады блок с таймером оставшегося времени</td>
						<td><input type="checkbox" checked></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
</div>

<?php _spm_view_msg("Система автоматически уведомит всех участников олимпиады по email о планировании данного мероприятия!", "info"); ?>

<div align="right">
	<a class="btn btn-danger btn-flat" href="index.php?service=olympiads">Отменить</a>
	<input type="reset" class="btn btn-warning btn-flat" value="Очистить">
	<input type="submit" class="btn btn-success btn-flat" value="Запланировать">
</div>
<?php
	SPM_footer();
?>
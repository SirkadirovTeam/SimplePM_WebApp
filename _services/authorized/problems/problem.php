<?php

/*
 * Copyright (C) 2018, Yurij Kadirov.
 * All rights are reserved.
 * Licensed under Apache License 2.0 with additional restrictions.
 *
 * @Author: Yurij Kadirov
 * @Website: https://sirkadirov.com/
 * @Email: admin@sirkadirov.com
 */

/*
 * Производим   включение   используемых
 * и  необходимых файлов исходного кода.
 */

include_once _SPM_includes_ . "ServiceHelpers/Olymp.inc";

/*
 * Всевозможные проверки безопасности
 */

isset($_GET['id']) or Security::ThrowError(_("Ідентифікатор задачі не вказано!"));
$_GET['id'] = abs((int)$_GET['id']);

/*
 * Устанавливаем название и Layout сервиса
 */

define("__PAGE_TITLE__", _("Задача") . " №" . @$_GET['id']);
define("__PAGE_LAYOUT__", "default");

/*
 * Запрашиваем доступ к глобальным переменным
 */

global $database;
global $_CONFIG;

/*
 * Получаем идентификатор текущего соревнования
 * для возможного ограничения доступного списка
 * задач.
 */

$associated_olymp = (int)(Security::getCurrentSession()["user_info"]->getUserInfo()["associated_olymp"]);

/*
 * Проверяем,  содержится ли текущая
 * задача  в  списке  доступных  для
 * решения задач во время проведения
 * текущего соревнования или урока.
 */

Olymp::CheckProblemInList($associated_olymp, $_GET['id']);

/*
 * Производим  выборку  информации
 * о текщей задаче из базы данных.
 */

// Формируем запрос на выборку
$query_str = "
    SELECT
      `spm_problems`.`id`,
      `spm_problems`.`difficulty`,
      `spm_problems`.`name`,
      `spm_problems`.`description`,
      `spm_problems`.`authorSolution`,
      `spm_problems`.`authorSolutionLanguage`,
      `spm_problems`.`input_description`,
      `spm_problems`.`output_description`,
      `spm_problems`.`adaptProgramOutput`,
      `spm_problems_categories`.`name` AS category_name,
      `spm_problems_tests`.`input` AS first_test_input,
      `spm_problems_tests`.`output` AS first_test_output
    FROM
      `spm_problems`
    LEFT JOIN
      `spm_problems_tests`
    ON
      `spm_problems`.`id` = `spm_problems_tests`.`problemId`
    RIGHT JOIN
      `spm_problems_categories`
    ON
      `spm_problems`.`category_id` = `spm_problems_categories`.`id`
    WHERE
      `spm_problems`.`id` = '" . $_GET['id'] . "'
    AND
      `spm_problems`.`enabled` = TRUE
    AND
      `spm_problems`.`authorSolution` IS NOT NULL
    AND
      `spm_problems`.`authorSolutionLanguage` IS NOT NULL
    ORDER BY
      `spm_problems_tests`.`id` ASC
    LIMIT
      1
    ;
";

// Выполняем запрос
$problem_info = $database->query($query_str);

// Если задача не найдена, выбрасываем исключение
if ($problem_info->num_rows == 0)
    Security::ThrowError("404");

// Получаем полную информацию о задаче
$problem_info = $problem_info->fetch_assoc();

/*
 * Получаем информацию о последней
 * попытке пользователя по текущей
 * задаче.
 *
 * Полученная информация будет
 * представлена пользователю в
 * виде инъекции в текущий по-
 * льзовательский интерфейс.
 */

// Формируем запрос на выборку данных
$query_str = "
    SELECT
      `submissionId`,
      `problemCode`,
      `testType`,
      `codeLang`
    FROM
      `spm_submissions`
    WHERE
      `userId` = '" . (int)Security::getCurrentSession()["user_info"]->getUserId() . "'
    AND
      `problemId` = '" . $_GET['id'] . "'
    AND
      `olympId` = '" . $associated_olymp . "'
    ORDER BY
      `time` DESC,
      `submissionId` DESC
    LIMIT
      1
    ;
";

// Выполняем запрос на выборку и получаем данные
$last_submission_info = @$database->query($query_str)->fetch_assoc();

?>

<style>
    #code_editor {
        width: 100%;
        height: 400px;
        margin: 0;
    }

    #custom_test {
        width: 100%;
        height: 100px;
        resize: none;
    }

    .card {
        margin: 0;
    }
</style>

<pre id="code_editor"><?=$last_submission_info['problemCode']?></pre>

<form action="<?=_SPM_?>index.php?cmd=problems/send_submission" method="post">

    <input type="hidden" name="problem_id" value="<?=$problem_info["id"]?>">

	<textarea
			id="code_place"
			name="code"
            hidden
            required
	></textarea>

    <textarea
            class="form-control text-white bg-dark"
            id="custom_test"
            name="custom_test"
            placeholder="<?=_("Користувацький тест для Debug-режиму тестування")?>"
            minlength="0"
            maxlength="65000"
    ></textarea>

    <div class="input-group">

        <select
                id="language_selector"
                name="submission_language"
                class="form-control"
                onchange="changeLanguage();"
                required
        >
            <option value><?=_("Виберіть мову програмування")?></option>

            <?php foreach ($_CONFIG->getCompilersConfig() as $compiler): if ($compiler['enabled']): ?>

                <option
                        value="<?=$compiler['language_name']?>"
                        langmode="<?=$compiler['editor_mode']?>"
                        <?=(
                                $compiler['language_name'] == @$last_submission_info['codeLang']
                                    ? "selected"
                                    : ""
                        )?>
                ><?=$compiler['display_name']?> (<?=$compiler['language_name']?>)</option>

            <?php endif; endforeach; ?>

        </select>

        <select
				name="submission_type"
				class="form-control"
				required
		>

            <option value><?=_("Виберіть тип перевірки")?></option>

            <option
                    value="syntax"
                    <?=(
                        @$last_submission_info['testType'] == "syntax"
                            ? "selected"
                            : ""
                    )?>
            ><?=_("Перевірка синтаксису")?></option>

            <option
                    value="debug"
                    <?=(
                        @$last_submission_info['testType'] == "debug"
                            ? "selected"
                            : ""
                    )?>
            ><?=_("Debug-режим")?></option>

            <option
                    value="release"
                    <?=(
                        @$last_submission_info['testType'] == "release"
                            ? "selected"
                            : ""
                    )?>
            ><?=_("Release-режим")?></option>

        </select>

        <button
                type="submit"
                class="btn btn-primary"
				onclick="
				    $('#code_place').text(ace.edit('code_editor').getValue());

				    return $('#code_place').length > 0;
                "
        ><?=_("Відправити")?></button>

    </div>

	<?php if (@(int)$last_submission_info['submissionId'] > 0): ?>

		<a
				class="btn btn-outline-dark btn-block"
				href="<?=_SPM_?>index.php/problems/result/?id=<?=$last_submission_info['submissionId']?>"
		><?=_("Результат останньої відправки")?></a>

	<?php endif; ?>
	<?php if (
			Security::CheckAccessPermissions(
				Security::getCurrentSession()["user_info"]->getUserInfo()['permissions'],
				PERMISSION::TEACHER | PERMISSION::ADMINISTRATOR
			) &&
			$problem_info['authorSolution'] != null &&
			$problem_info['authorSolutionLanguage'] != null
	): ?>
		<button
				type="button"
				class="btn btn-outline-secondary btn-block"
				style="margin: 0;"
				data-toggle="modal"
				data-target="#author_solution"
		><?=_("Отримати авторське рішення")?></button>

		<div class="modal fade" id="author_solution" tabindex="-1" role="dialog">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title"><?=_("Авторське рішення задачі")?> (<?=$problem_info['authorSolutionLanguage']?>)</h5>
						<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
					</div>
					<pre class="modal-body"><?=$problem_info['authorSolution']?></pre>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal"><?=_("Закрити вікно")?></button>
					</div>
				</div>
			</div>
		</div>

	<?php endif; ?>

</form>

<div class="card">
    <div class="card-body text-center" style="padding: 5px;">

		<strong>
            <?=$problem_info["id"]?>.
            <?=$problem_info["name"]?>
            <span class="badge badge-info"><?=$problem_info["category_name"]?></span>
            <span class="badge badge-success"><?=$problem_info["difficulty"]?> points</span>
        </strong>

    </div>
</div>

<div class="card">
    <div class="card-body text-justify">
        <?=htmlspecialchars_decode(trim($problem_info["description"]))?>
    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Опис вхідного потоку")?></strong></h6>

                <p
						class="card-text text-justify"
				><?=strlen($problem_info["input_description"]) > 0 ? $problem_info["input_description"] : _("Немає вхідних даних")?></p>

            </div>

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Опис вихідного потоку")?></strong></h6>

                <p
						class="card-text text-justify"
				><?=strlen($problem_info["output_description"]) > 0 ? $problem_info["output_description"] : _("Немає вихідних даних")?></p>

            </div>

        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">

        <div class="row">

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Приклад вхідного потоку")?> (input.dat)</strong></h6>

                <pre
						class="card-text text-justify"
				><?=strlen($problem_info["first_test_input"]) > 0 ? $problem_info["first_test_input"] : _("Немає вхідних даних")?></pre>

            </div>

            <div class="col-md-6 col-sm-12" style="padding: 10px;">

                <h6 class="card-title"><strong><?=_("Приклад вихідного потоку")?> (output.dat)</strong></h6>

                <pre
						class="card-text text-justify"
				><?=strlen($problem_info["first_test_output"]) > 0 ? $problem_info["first_test_output"] : _("Немає вихідних даних")?></pre>

            </div>

        </div>

    </div>
</div>

<script src="<?=_SPM_assets_?>_plugins/ace/ace.js"></script>
<script>

    function changeLanguage()
    {

        ace.edit('code_editor').session.setMode(
            'ace/mode/' + $('#language_selector option:selected').attr('langmode')
        );

    }

    document.addEventListener('DOMContentLoaded', function() {

        var editor = ace.edit("code_editor");
        editor.setTheme("ace/theme/dracula");

        changeLanguage();

    });

    //editor.session.setMode("ace/mode/c_cpp");
</script>
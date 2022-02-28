<!DOCTYPE html>
<html>
<head>
    <title>DocSaver</title>
    <link rel="stylesheet" type="text/css" href="[+manager_url+]media/style/[+theme+]/style.css" />
    <link rel="stylesheet" href="[+manager_url+]media/style/common/font-awesome/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="[+site_url+]assets/js/easy-ui/themes/modx/easyui.css"/>
    <script type="text/javascript" src="[+manager_url+]media/script/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="[+site_url+]assets/js/easy-ui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="[+site_url+]assets/js/easy-ui/locale/easyui-lang-ru.js"></script>
    <script type="text/javascript" src="[+site_url+]assets/modules/docsaver/js/module.js"></script>
    <script type="text/javascript">
        var connector = '[+connector+]';
        var processing = false;
    </script>
    <style>
        .sectionBody > .tab-pane > .tab-page {
            margin-top:0;
        }
    </style>
</head>
<body>
<h1 class="pagetitle">
    <span class="pagetitle-text">
    DocSaver
  </span>
</h1>
<div id="actions">
    <ul class="actionButtons">
        <li><a href="#" onclick="document.location.href='index.php?a=106';">Закрыть модуль</a></li>
    </ul>
</div>
<div class="sectionBody">
    <div class="dynamic-tab-pane-control tab-pane">
        <div class="tab-page">
            <div class="tab-header">Выбор документов</div>
            <div class="tab-body">
                <form id="rangeForm">
                    <label for="range">Список документов (по умолчанию - все)</label>
                    <div class="input-group">
                        <input id="range" class="form-control" type="text">
                    </div>
                    <label for="addWhere">Дополнительное условие</label>
                    <div class="input-group">
                        <input id="addWhere" class="form-control" type="text">
                        <span class="input-group-btn">
                            <input class="btn" type="submit" value="Отправить">
                        </span>
                    </div>
                </form>
                <br>
                <p><b>Можно использовать следующий синтаксис при задании списка документов (вместо "n" указывайте число ID ресурса):</b></p>
<ul>
    <li><b>n*</b> - изменить свойства ресурса с ID=n и непосредственных дочерних ресурсов;</li>
    <li><b>n**</b> - изменить свойства ресурса с ID=n и ВСЕХ его дочерних ресурсов;</li>
    <li><b>n-n2</b> - изменить свойства для всех ресурсов, ID которых находятся в указанном диапазоне;</li>
    <li><b>n</b> - изменить свойства для одного ресурса с ID=n;</li>
    <li><b>n*,n**,n-n2,n</b> - можно сразу указать несколько диапазонов, разделяя их запятыми.</li>
</ul><br><p><b>Пример:</b> 1*,4**,2-20,25 - будут изменены свойства для ресурса с ID=1 и его непосредственных дочерних ресурсов, ресурса с ID=4 и всех его дочерних ресурсов, ресурсов с ID в диапазоне от 2 до 20, и ресурса с ID=25.</p>
                <p>Имена полей в дополнительном условии выборки задаются с префиксом c (например, c.published=1)</p>
            </div>
        </div>
    </div>
</div>
<div id="dialog">
    <div class="dialogContent">
        <div id="progress" style="display:none;"></div>
    </div>
</div>
<script>
    
</script>
</body>
</html>

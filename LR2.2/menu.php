<?php
include 'header.php';
?>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <label for="numeric-filter">Фильтрация по цене</label>
            <?php echo '<input type="number" id="numeric-filter" name="numericFilter" class="form-control" placeholder="Введите цену" value="' . (isset($_GET['numericFilter']) ? $_GET['numericFilter'] : '') . '">'; ?>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <label for="select-filter">Фильтрация по локации</label>
            <?php
            $options = array(
                'option1' => 'г. Волгоград, ул. маршала Василевского, 4',
                'option2' => 'г. Волгоград, ул. Пархоменко, 8А',
                'option3' => 'г. Волгоград, ул. Николая Отрады, 10',
                'option4' => 'г. Волгоград, ул. Комсомольская, 12'
            );
            echo '<select id="select-filter" name="selectFilter" class="form-control">';
            echo '<option value="">Выберите локацию</option>';
            $i = 1;
            foreach ($options as $value => $label) {
                $selected = (isset($_GET['selectFilter']) && $_GET['selectFilter'] == $i) ? ' selected' : '';
                echo "<option value='$i'$selected>$label</option>";
                $i++;
            }
            echo '</select>';
            ?>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <label for="text-filter">Фильтрация по названию</label>
            <?php echo '<input type="text" id="text-filter" name="textFilter" class="form-control" placeholder="Введите название" value="' . (isset($_GET['textFilter']) ? $_GET['textFilter'] : '') . '">'; ?>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <label for="text-filter">Фильтрация по рецептуре</label>
            <?php echo '<input type="text" id="text-filter-1" name="textFilter1" class="form-control" placeholder="Введите часть рецептуры" value="' . (isset($_GET['textFilter1']) ? $_GET['textFilter1'] : '') . '">'; ?>
        </div>
        <div class="col-md-4"></div>
    </div>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary" id="filter-btn">Фильтровать</button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-default" id="clear-btn" onclick="clearForm()">Очистить</button>
        </div>
        <div class="col-md-3"></div>
    </div>
</form>
<h1 style='text-align: center;'>Меню</h1>
<script>
    $(document).ready(function() {
        // Trigger the AJAX request when the page loads
        var numericFilter = $("#numeric-filter").val();
        var selectFilter = $("#select-filter").prop("selectedIndex");
        var textFilter = $("#text-filter").val();
        var textFilter1 = $("#text-filter-1").val();

        $.ajax({
            type: "GET",
            url: "logic.php",
            data: {
                numericFilter: numericFilter,
                selectFilter: selectFilter,
                textFilter: textFilter,
                textFilter1: textFilter1
            },
            dataType: 'html',
            success: function(response) {
                $("#result-table").html(response);
            }
        });
    });
</script>
<div id="result-table"></div>
<script>
    $(document).ready(function() {
        $("#filter-btn").on("click", function() {
            var numericFilter = $("#numeric-filter").val();
            var selectFilter = $("#select-filter").prop("selectedIndex");
            var textFilter = $("#text-filter").val();
            var textFilter1 = $("#text-filter-1").val();

            $.ajax({
                type: "GET",
                url: "logic.php",
                data: {
                    numericFilter: numericFilter,
                    selectFilter: selectFilter,
                    textFilter: textFilter,
                    textFilter1: textFilter1
                },
                dataType: 'html',
                success: function(response) {
                    $("#result-table").html(response);
                }
            });
        });
    });
</script>
<script>
    function clearForm() {
        document.getElementById("numeric-filter").value = "";
        document.getElementById("select-filter").value = "";
        document.getElementById("text-filter").value = "";
        document.getElementById("text-filter-1").value = "";
    }
</script>

<div id="result-table"></div>
<?php
include 'footer.php';
?>

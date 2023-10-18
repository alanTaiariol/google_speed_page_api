<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>
<div class="container offset-md-2">
    <div class="row">
        <div class="col-md-10">
            <h1>Obtain Metrics</h1>

                <div class="mb-3">
                    <label for="url" class="form-label">URL:</label>
                    <input type="text" class="form-control" id="url" name="url" required>
                </div>

                <div class="mb-3">
                    <label class="form-check-label">Categories:</label><br>
                    <button class="btn btn-primary" id="every_categories">select every categories</button> <br><br>
                    @foreach($categories as $category)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="{{ $category->name }}" name="categories[]" value="{{ $category->name }}">
                            <label class="form-check-label" for="{{ $category->id }}">{{ $category->name }}</label>
                        </div>
                    @endforeach
                </div>
                

                <div class="mb-3">
                    <label for="strategy" class="form-label">Select Strategy:</label>
                    <select class="form-select" id="strategy" name="strategy" required>
                        @foreach($strategies as $strategy)
                        <option value="{{ $strategy->name }}">{{ $strategy->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button id="submit_button" class="btn btn-primary">get metrics</button>
        </div>
        <div class="row">
            <div class="col-md-10">
                <div id="myResults">

                </div>
                <div id="alert_success" class="alert alert-success alert-dismissible fade hide" role="alert">
                    <strong>Success!</strong> Your operation was completed successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
        
        
        
        <input type="hidden" id="scores" name="scores[]" value="">
        
        <div class="row">
            <div class="col-md-8">
                <h3>Metrics saved</h3>
                <table class="table">
                <thead>
                    <tr>
                    <th scope="col">URL</th>
                    <th scope="col">ACCESSIBILITY</th>
                    <th scope="col">BEST PRACTICES</th>
                    <th scope="col">PERFORMANCE</th>
                    <th scope="col">PWA</th>
                    <th scope="col">SEO</th>
                    <th scope="col">STRATEGY</th>
                    <th scope="col">DATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($metricHistoryRun as $metric)
                     <tr>
                         <td>{{$metric->url }}</td>
                         <td>{{$metric->accesibility_metric == 'null' ? '' : $metric->accesibility_metric }}</td>
                         <td>{{$metric->best_practices_metric == 'null' ? '' : $metric->best_practices_metric }}</td>
                         <td>{{$metric->performance_metric == 'null' ? '' : $metric->performance_metric }}</td>
                         <td>{{$metric->pwa_metric == 'null' ? '' : $metric->pwa_metric }}</td>
                         <td>{{$metric->seo_metric == 'null' ? '' : $metric->seo_metric}}</td>
                         <td>{{$metric->strategy->name}}</td>
                         <td>{{$metric->created_at}}</td>
                    </tr>
                     @endforeach
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
<script>3


  $(document).ready(function () {
        $('#submit_button').click(function () {
                    
            let apiUrl = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';
            let apiKey = 'AIzaSyCxQtX6NDo8fxOTxmjukfDW234-HrZQqe4'; 
            let url = $("#url").val(); 

            const selectedCategories = [];
            let categories = $('input[name="categories[]"]:checked').each(function() {
                selectedCategories.push($(this).val());
            });

            let categoriesString = "";
            selectedCategories.forEach(function(category){
                categoriesString += "&category=" + category;
            });

            let strategy =  $("#strategy").val();  // Replace with the desired strategy

            const completeUrl = `${apiUrl}?url=${url}&key=${apiKey}${categoriesString}&strategy=${strategy}`;
            
            $.ajax({
                url: completeUrl,
                type: 'GET',
                success: function (response) {
                    var titles_scores = [];
                    
                    $.each(response, function(key, value) {
                        if(key == "lighthouseResult") {
                            $.each(value, function(lighthouseResult_key, lighthouseResult_value) {
                                if(lighthouseResult_key == "categories") {
                                    $.each(lighthouseResult_value, function(category_key, category_value){
                                        titles_scores.push({title : category_value.title, score: category_value.score});
                                    });
                                }
                            });
                        }
                    });

                    let table = `<table class="table">
                                    <thead>
                                        <tr>`;
                        table +=`<th scope="col">URL</th>`;
                        $.each(titles_scores, function(key,val) {
                                table +=`<th scope="col">${val.title.toUpperCase()}</th>`;
                        });
                        table +=`<th scope="col">STRATEGY</th>`;
                        table +=`<th scope="col">ACTIONS</th>`;
                        table += `</tr></thead><tbody><tr>`;
                        
                        table +=`<td>${url}</td>`;

                        $.each(titles_scores, function(key,val) {
                                table +=`<td>${val.score}</td>`;
                        });             

                        table +=`<td>${strategy}</td>`;
                        table +=`<td><button id="save_metrics" class="btn btn-success">save metrics</button></td>`;
                        table +=`</tr> </tbody> </table>`;

                        
                        $("#myResults").html(table);

                        let scores = JSON.stringify(titles_scores);
                        $("#scores").val(scores);
                },
                error: function (xhr, status, error) {
                    console.error(xhr);
                    alert('Error occurred');
                }
            });
        });

        $('#myResults').on('click', '#save_metrics', function () {
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            let scores = $('input[name="scores[]"]').val();
            let scoresParse = JSON.parse(scores);
            let url = $("#url").val();
            let strategy = $("#strategy").val() == 'DESKTOP' ? 1 : 2;

            let data = {
                scores: scoresParse,
                url: url,
                strategy: strategy,
            };

            $.ajax({
                url: '/create', 
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function (response) {
                    
                    console.log('successfully POST');
                    console.log("metricHistoryRun")
                    console.log(response.metricHistoryRun);

                    if(response) {
                        $("#alert_success").removeClass('hide');

                        $("#alert_success").addClass('show');
                    }
                },
                error: function (xhr, status, error) {
                    
                    console.error('Error POST');
                    console.error(xhr);
                }
            });
        });

        $('#every_categories').click(function () {
            if($(this).hasClass('allChecked')) {
                $('input[name="categories[]"]').prop('checked', false);
                $(this).removeClass('allChecked');
            } else {
                $('input[name="categories[]"]').prop('checked', true);
                $(this).addClass('allChecked');
            }
        
    });
    });
        
</script>



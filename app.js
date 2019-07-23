$(document).ready(function(){
    $.ajax({
        url:"http://localhost/obe/barGraph.php",
        method:"GET",
        success: function(data){
            var numbers = [];
            var names = [];

            for(var i in data){
                numbers.push(data[i].marks_obtained);
                names.push(data[i].S_name);
            }

            var chartdata = {
                labels:names,
                datasets:[{
                    label: 'Quiz Score',
                    backgroundColor:[
                    'green',
                    'purple',
                    'yellow',
                    'red',
                    'blue',
                    'pink'
                ],
                data:numbers
                }],

                
            }

            var ctx = $("#myChart");

            var barGraph = new Chart(ctx,{
                type: 'bar',
                data: chartdata,

                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                max:20
                            }
                        }]
                    }
                }
            })

        },
        error: function(data){
            console.log(data);
        }
    });
});

<div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6 inline-block">
@if($ratingsBreakdown)
  <div class="flex justify-between pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center">
      <h5 class="text-xl text-left font-bold dark:text-white">Rating Distribution</h5>
    </div>
  </div>
  <div class="explanation text-sm text-gray-500 dark:text-gray-400 mb-4 w-full text-left">
    This chart displays the rating distribution of active athletes in the <strong class="text-gray-900 dark:text-white">currently filtered</strong> ladder.
    </div>
  <div id="column-chart"></div>
    <div class="hidden grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
      <div class="flex justify-between items-center pt-1">
        <!-- Button -->
        <button
          id="dropdownDefaultButton"
          data-dropdown-toggle="lastDaysdropdown"
          data-dropdown-placement="bottom"
          class="hiddentext-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
          type="button">
          Last 7 days
          <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
          </svg>
        </button>
        <!-- Dropdown menu -->
        <div id="lastDaysdropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 7 days</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 30 days</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 90 days</a>
              </li>
            </ul>
        </div>
        <a
          href="#"
          class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500  hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
          Leads Report
          <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
        </a>
      </div>
    </div>
@else
    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
        No rating distribution data available for the current selection.
    </div>
@endif
</div>
<script>

    const options = {
    colors: ["#7E3AF2", "#FDBA8C", "#1A56DB"],
    series: [
        {
        name: "Total",
        color: "#1A56DB",
            data: [
                @foreach($ratingsBreakdown as $rating => $count)
                    { x: "{{ $rating }}", y: {{ $count }} },
                @endforeach
            ],
        },
        
    ],
    chart: {
        type: "bar",
        height: "320px",
        fontFamily: "Inter, sans-serif",
        toolbar: {
            show: false,
        },
        foreColor: document.documentElement.classList.contains('dark') ? '#fff' : '#374151',
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "70%",
            borderRadiusApplication: "end",
            borderRadius: 8,
        },
    },
    tooltip: {
        shared: true,
        intersect: false,
        style: {
            fontFamily: "Inter, sans-serif",
        },
    },
    states: {
        hover: {
        filter: {
            type: "darken",
            value: 1,
        },
        },
    },
    stroke: {
        show: true,
        width: 0,
        colors: ["transparent"],
    },
    grid: {
        show: false,
        strokeDashArray: 4,
        padding: {
        left: 2,
        right: 2,
        top: -14
        },
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: false,
    },
    xaxis: {
        floating: false,
        labels: {
        show: true,
        style: {
            fontFamily: "Inter, sans-serif",
            cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
        },
        },
        axisBorder: {
        show: false,
        },
        axisTicks: {
        show: false,
        },
    },
    yaxis: {
        show: true,
        labels: {
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#fff' : '#374151',
            }
        }
    },
    fill: {
        opacity: 1.0,
    },
    }

    if(document.getElementById("column-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("column-chart"), options);
    
    // Check if there's data before rendering the chart
    if ($(JSON.parse('@json($ratingsBreakdown)')).length > 0) {
        chart.render();    
    }
    // Listen for theme changes and update chart colors
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class' && 
                mutation.target === document.documentElement) {
                const isDarkMode = document.documentElement.classList.contains('dark');
                chart.updateOptions({
                    chart: {
                        foreColor: isDarkMode ? '#fff' : '#374151'
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: isDarkMode ? '#fff' : '#374151'
                            }
                        }
                    }
                });
            }
        });
    });
    
    observer.observe(document.documentElement, { attributes: true });
    }

</script>

<div class="max-w-sm w-full bg-white rounded-lg shadow-sm dark:bg-gray-800 p-4 md:p-6 inline-block">
  <div class="flex flex-col">
      <div class="pb-4 mb-4 border-b border-gray-200 dark:border-gray-700 w-full">
          <h5 class="text-xl text-left font-bold leading-none text-gray-900 dark:text-white pe-1">Gender Group Breakdown</h5>
      </div>
      <div class="explanation text-sm text-gray-500 dark:text-gray-400 mb-4 w-full text-left">
        This chart displays the gender distribution of active athletes in the <strong class="text-gray-900 dark:text-white">entire</strong> QLD ladder.
      </div>
      <div class="flex justify-left items-center">
          <svg data-popover-target="chart-info" data-popover-placement="bottom" class="hidden w-3.5 h-3.5 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white cursor-pointer ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm0 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm1-5.034V12a1 1 0 0 1-2 0v-1.418a1 1 0 0 1 1.038-.999 1.436 1.436 0 0 0 1.488-1.441 1.501 1.501 0 1 0-3-.116.986.986 0 0 1-1.037.961 1 1 0 0 1-.96-1.037A3.5 3.5 0 1 1 11 11.466Z"/>
          </svg>
          <div data-popover id="chart-info" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 w-72 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400">
              <div class="p-3 space-y-2">
                  <h3 class="font-semibold text-gray-900 dark:text-white">Gender Distribution</h3>
                  <p>This chart shows the gender distribution of the active athletes in the entire.</p>
              </div>
              <div data-popper-arrow></div>
          </div>
        </div>
  </div>

  <div>
    <div class="flex hidden" id="age-groups">
      <div class="flex items-center me-4">
          <input id="juniors" type="checkbox" value="juniors" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="juniors" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Juniors</label>
      </div>
      <div class="flex items-center me-4">
          <input id="seniors" type="checkbox" value="seniors" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
          <label for="seniors" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Seniors</label>
      </div>
    </div>
  </div>

  <!-- Donut Chart -->
  <div class="p-0 text-white" id="donut-chart"></div>

  <div class="hidden grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
    <div class="flex justify-between items-center pt-5">
      <!-- Button -->
      <button
        id="dropdownDefaultButton"
        data-dropdown-toggle="lastDaysdropdown"
        data-dropdown-placement="bottom"
        class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white"
        type="button">
        Last 7 days
        <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
      </button>
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
        Traffic analysis
        <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
        </svg>
      </a>
    </div>
  </div>
</div>
<script>
        
    const getChartOptions = () => {
    return {
        series: @json($genderCounts),
        colors: ["#1C64F2", "#16BDCA", "#FDBA8C", "#E74694"],
        chart: {
            height: 320,
            width: "100%",
            type: "donut",
            foreColor: document.documentElement.classList.contains('dark') ? '#fff' : '#374151',
        },
        stroke: {
            colors: ["transparent"],
            lineCap: "",
        },
        plotOptions: {
        pie: {
            donut: {
            labels: {
                show: true,
                name: {
                    show: true,
                    fontFamily: "Inter, sans-serif",
                    offsetY: 20,
                },
                total: {
                    showAlways: true,
                    show: true,
                    label: "Gender Ratio",
                    fontFamily: "Inter, sans-serif",
                    formatter: function (w) {
                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        // Calculate percentages and round to whole numbers
                        const percentagesWithIndices = w.globals.seriesTotals.map((val, index) => ({
                            percentage: Math.round((val / total) * 100),
                            index: index
                        }));
                        // Sort percentages in descending order
                        percentagesWithIndices.sort((a, b) => b.percentage - a.percentage);
                        // Extract just the percentage values
                        const sortedPercentages = percentagesWithIndices.map(item => item.percentage);
                        // Create ratio string with bigger percentages first
                        return sortedPercentages.join(' : ');
                    },
                },

                /*
                grand_total: {
                    showAlways: true,
                    show: true,
                    label: "Total Athletes",
                    fontFamily: "Inter, sans-serif",
                    formatter: function (w) {
                        const sum = w.globals.seriesTotals.reduce((a, b) => {
                        return a + b
                        }, 0)
                        return sum
                    },
                },
                */

                value: {
                show: true,
                fontFamily: "Inter, sans-serif",
                offsetY: -20,
                formatter: function (value) {
                    return value
                },
                },
            },
            size: "60%",
            },
        },
        },
        grid: {
        padding: {
            top: -2,
        },
        },
        labels: @json($genderLabels),
        dataLabels: {
        enabled: false,
        },
        legend: {
        position: "bottom",
        fontFamily: "Inter, sans-serif",
        },
        yaxis: {
        labels: {
            formatter: function (value) {
            return value
            },
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#fff' : '#374151',
            }
        },
        },
        xaxis: {
        labels: {
            formatter: function (value) {
            return value
            },
            style: {
                colors: document.documentElement.classList.contains('dark') ? '#fff' : '#374151',
            }
        },
        axisTicks: {
            show: false,
        },
        axisBorder: {
            show: false,
        },
        },
    }
    }

    if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
    const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
    chart.render();

    // Get all the checkboxes by their class name
    const checkboxes = document.querySelectorAll('#age-groups input[type="checkbox"]');

    // Function to handle the checkbox change event
    function handleCheckboxChange(event, chart) {

        const checkbox = event.target;
        if (checkbox.checked) {

            switch(checkbox.value) {
                case 'juniors':
                chart.updateSeries(@json($genderCounts));
                break;
                case 'seniors':
                chart.updateSeries(@json($genderCounts));
                break;
                default:
                chart.updateSeries(@json($genderCounts));
            }

            console.log(checkbox.value);
            // Uncheck all other checkboxes in the age-groups container
            const ageGroupsContainer = document.getElementById('age-groups');
            const otherCheckboxes = ageGroupsContainer.querySelectorAll('input[type="checkbox"]:not([value="' + checkbox.value + '"])');
            otherCheckboxes.forEach(otherCheckbox => {
                otherCheckbox.checked = false;
            });

        } else {
            chart.updateSeries(@json($genderCounts));
        }
    }

    // Attach the event listener to each checkbox
    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', (event) => handleCheckboxChange(event, chart));
    });
    
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
                    },
                    xaxis: {
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

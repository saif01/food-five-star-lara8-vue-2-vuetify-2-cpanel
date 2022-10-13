<script>
// Pie, Doughnut, Line, Bar

import { Doughnut } from "vue-chartjs";

export default {
  props: ["chartData", "chartLabel"],

  extends: Doughnut,
  data() {
    return {
      datacollection: {
        labels: this.chartLabel,

        datasets: [
          {
            label: "Data One",
            //backgroundColor: ['#f87979', 'green'],
            backgroundColor: this.randColors(this.chartLabel.length),
            pointBackgroundColor: "white",
            borderWidth: 1,
            pointBorderColor: "#29465B",
            // data: [60, 40, 20, 50, 90, 10, 20, 40, 50, 70, 90, 100],
            data: this.chartData,
          },
        ],
      },
      options: {
        tooltips: {
          callbacks: {
            title: function (tooltipItem, data) {
              return data["labels"][tooltipItem[0]["index"]];
            },
            label: function (tooltipItem, data) {
              var tooltipData =
                data["datasets"][0]["data"][tooltipItem["index"]];

              var finalData =
                tooltipData >= 1000
                  ? "৳ " +
                    tooltipData.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                  : "৳ " + tooltipData;
              return finalData;
            },
          },
        },
        scales: {
          yAxes: [
            {
              ticks: {
                beginAtZero: true,
                display: false,
              },
              gridLines: {
                display: false,
              },
            },
          ],
          xAxes: [
            {
              ticks: {
                display: false,
              },
              gridLines: {
                display: false,
              },
            },
          ],
        },
        legend: {
          display: true,
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    };
  },

  methods: {
    // random color generator
    randColors(val = 2) {
      // empty Array
      const colors = [];
      for (let i = 1; i <= val; i++) {
        let color = "#" + (Math.random().toString(16) + "0000000").slice(2, 8);
        // colors push in array
        colors.push(color);
      }
      return colors;
    },
  },

  mounted() {
    this.renderChart(this.datacollection, this.options);
  },
};
</script>
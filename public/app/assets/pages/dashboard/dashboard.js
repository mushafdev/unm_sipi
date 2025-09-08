am5.ready(function() {
    var root = am5.Root.new("chart-penjualan");

    
    root.setThemes([
        am5themes_Animated.new(root)
    ]);

    root.interfaceColors.set("primaryButton", am5.color(0xFF1493));

    var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: true,
        panY: true,
        wheelX: "panX",
        wheelY: "zoomX",
        pinchZoomX: true
    }));

    var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
        behavior: "none"
    }));
    cursor.lineY.set("visible", false);

    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        categoryField: "bulan",
        renderer: am5xy.AxisRendererX.new(root, {
            minGridDistance: 30,
            labels: { fillOpacity: 0.6 },
            strokeOpacity: 0.05
        }),
        tooltip: am5.Tooltip.new(root, {})
    }));

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        renderer: am5xy.AxisRendererY.new(root, {
            visible: false,
            strokeOpacity: 0
        }),
        min: 0
    }));

    var series = chart.series.push(am5xy.SmoothedXLineSeries.new(root, {
        name: "Penjualan",
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: "jumlah",
        categoryXField: "bulan",
        tooltip: am5.Tooltip.new(root, {
            labelText: "[bold]{bulan}:[/] Rp{valueY}"
        }),
        tensionX: 0.8 // nilai antara 0 - 1 (semakin tinggi, semakin halus)
    }));

    series.setAll({
        stroke: am5.color(0xFF1493),
        fill: am5.color(0xFF1493)
    });

    series.strokes.template.setAll({
        strokeWidth: 3,
        stroke: am5.color(0xFF1493)
    });

    series.fills.template.setAll({
        visible: true,
        fillOpacity: 0.15,
        fill: am5.color(0xFF1493)
    });

    series.bullets.push(function () {
        return am5.Bullet.new(root, {
            sprite: am5.Circle.new(root, {
                radius: 4,
                fill: am5.color(0xFF1493),
                strokeWidth: 2,
                stroke: am5.color(0xffffff)
            })
        });
    });

    // var data = [];
    fetch($('#chart-penjualan').data('url'))
    .then(res => res.json())
    .then(data => {
        xAxis.data.setAll(data);
        series.data.setAll(data);
        series.appear(1000);
        chart.appear(1000, 100);
    });

    series.appear(1000);
    chart.appear(1000, 100);

   // === PIE CHART KATEGORI PRODUK ===
  let rootProduk = am5.Root.new("chart-kategori-produk");

  rootProduk.setThemes([
      am5themes_Animated.new(rootProduk)
  ]);

  let chartProduk = rootProduk.container.children.push(
      am5percent.PieChart.new(rootProduk, {
          layout: rootProduk.verticalLayout
      })
  );

  let seriesProduk = chartProduk.series.push(
      am5percent.PieSeries.new(rootProduk, {
          valueField: "value",
          categoryField: "category"
      })
  );

  // Data asli (tanpa warna manual)
  let dataProduk = [];

  fetch($('#chart-kategori-produk').data('url')) // pastikan div memiliki data-url
      .then(res => res.json())
      .then(res => {
          dataProduk = res;

          // Warna default
          let defaultColors = [
              0xF8BBD0, 0xCE93D8, 0xB2EBF2, 0xFFF59D, 0xA5D6A7, 0xFFAB91,
              0x80DEEA, 0xE6EE9C, 0xD7CCC8, 0xBCAAA4
          ];

          dataProduk.forEach((item, index) => {
              item.color = am5.color(defaultColors[index % defaultColors.length]);
          });

          seriesProduk.data.setAll(dataProduk);
      });

  // Warna slice dan label tetap:
  seriesProduk.events.on("datavalidated", function () {
      seriesProduk.slices.each(function (slice) {
          let color = slice.dataItem.dataContext.color;
          if (color) {
              slice.set("fill", color);
              slice.set("stroke", am5.color(0xffffff));
          }
      });
  });

  seriesProduk.labels.template.setAll({
      fontSize: 13,
      fontWeight: "400",
      fill: am5.color(0x555555)
  });

  seriesProduk.appear(1000, 100);
  chartProduk.appear(1000, 100);


// === PIE CHART KATEGORI TINDAKAN ===
  let rootTindakan = am5.Root.new("chart-kategori-tindakan");

  rootTindakan.setThemes([
      am5themes_Animated.new(rootTindakan)
  ]);

  rootTindakan.interfaceColors.set("neutral", am5.color(0x000000));
  rootTindakan.interfaceColors.set("primaryButton", am5.color(0x000000));

  let chartTindakan = rootTindakan.container.children.push(
      am5percent.PieChart.new(rootTindakan, {
          layout: rootTindakan.verticalLayout
      })
  );

  let seriesTindakan = chartTindakan.series.push(
      am5percent.PieSeries.new(rootTindakan, {
          valueField: "value",
          categoryField: "category"
      })
  );

  // Data asli tanpa warna manual
 fetch($('#chart-kategori-tindakan').data('url'))
    .then(res => res.json())
    .then(dataTindakan => {
        let defaultColorsTindakan = [
            0xFFCDD2, 0xA5D6A7, 0x90CAF9, 0xFFE082,
            0xCE93D8, 0xB2EBF2, 0xFFF59D, 0xFFAB91
        ];

        dataTindakan.forEach((item, index) => {
            item.color = am5.color(defaultColorsTindakan[index % defaultColorsTindakan.length]);
        });

        seriesTindakan.data.setAll(dataTindakan);
    });

    seriesTindakan.events.on("datavalidated", function () {
        seriesTindakan.slices.each(function (slice) {
            let color = slice.dataItem.dataContext.color;
            if (color) {
                slice.set("fill", color);
                slice.set("stroke", am5.color(0xffffff));
            }
        });
    });

    seriesTindakan.labels.template.setAll({
        fontSize: 13,
        fontWeight: "400",
        fill: am5.color(0x555555)
    });

    seriesTindakan.appear(1000, 100);
    chartTindakan.appear(1000, 100);


});
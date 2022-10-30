import * as echarts from 'echarts/core';
import {
  TitleComponent,
  TooltipComponent,
} from 'echarts/components';
import { PieChart } from 'echarts/charts';
import { LabelLayout } from 'echarts/features';
import { CanvasRenderer } from 'echarts/renderers';

echarts.use([
  TitleComponent,
  TooltipComponent,
  PieChart,
  CanvasRenderer,
  LabelLayout
]);

var chartDom = document.getElementById('top-selling-pie-chart');
var chart = echarts.init(chartDom);
window.piechart = chart

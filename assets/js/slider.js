import * as noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css';
import '../styles/homepage.scss';

var slider = document.getElementById('slider');

const min = document.getElementById('min')
const max = document.getElementById('max')

const searchParams = new URLSearchParams(window.location.search);

const searchMinValue = searchParams.get('min');
const searchMaxValue = searchParams.get('max');

const startMinValue = searchMinValue ? parseInt(searchMinValue) : 0;
const startMaxValue = searchMaxValue ? parseInt(searchMaxValue) : 400;

const range = noUiSlider.create(slider, {
    start: [startMinValue, startMaxValue],
    connect: true,
    step: 1,
    range: {
        'min': 0,
        'max': 400
    }
});

range.on('slide', function(values, handle){
    if (handle === 0){
        min.value = Math.round(values[0]) 
    }
    if (handle === 1){
        max.value = Math.round(values[1]) 
    }
})
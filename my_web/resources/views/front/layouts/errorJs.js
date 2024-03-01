rangeSlider = $('.js-range-slider').ionRangeSlider({
    type: "double",
    min: 0,
    max: 1500,
    from: 0,
    step: 10,
    to: 500,
    skin: "round",
    max_postfix: "+",
    prefix: "$",
    onFinish: function() {
        applyFilters()
    }
});
// Saving it's instance to const
const slider = $(".js-range-slider").data("ionRangeSlider");

$('.brand-label').change(function() {
    apply_filters();
});

function apply_filters() {
    const brands = [];
    $('.brand-label').each(function() {
        if ($(this).is(':checked') == true) {
            brands.push($(this).val());
        }
    });
    console.log(brands.toString());
    
    const url = '{{ url()->current() }}?';
    url += '&price_min='+slider.result.from+'&price_max='+slider.result.to;

    window.location.href = url+'&brand='+brands.toString();
};
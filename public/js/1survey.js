$(function() {
    $('.gradient_background').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
});

$('select[name="gradient_color"]').on('change', function() {
   $('.gradient_background').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
    $('.preview-gradient').css('background', GradientList[$('select[name="gradient_color"]').val()][0]);
});

$('input[name=border_color]').on('change', function() {
    $('#preview-text').css('border', '1px solid ' + $(this).val())
})
$('input[name=char_color]').on('change', function() {
    $('#preview-text').css('color', $(this).val());
})
$('input[name=callout_color]').on('change', function() {
    $('#preview-text').css('background', $(this).val());
})
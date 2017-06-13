/**
 * Created by h.sarani on 1/29/2017.
 */
$(window).ready(function () {
    var parent = document.getElementById('parent');

    var picker = new Picker(parent);

    picker.on_done = function(colour) {

        parent.style.background = colour.rgba().toString();
        $('.text-field').css('color', colour.rgba().toString());

        parent.innerHTML = colour.hex().toString();

        var rgba = colour.rgba();

        // document.getElementById('rgba').innerHTML = '<span>R:</span> ' + rgba.r + '  <span>G:</span> ' + rgba.g + '  <span>B:</span> ' + rgba.b + '  <span>A:</span> ' + rgba.a.toFixed(2);
    };

    $('#parent').click(function(e) {

        picker.show();

        e.preventDefault()
    });
});
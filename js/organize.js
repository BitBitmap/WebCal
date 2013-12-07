$(function() {
  var last_used_event_id = 0;
  $('.add-date').click(function() {
    var new_date_entry = $($('.event-date').get(0)).clone();
    new_date_entry.attr('name', 'date-' + ++last_used_event_id)
      .datepicker()
      .val('')
    $('.date-entries').append(new_date_entry);
  })

  $('.remove-date').click(function() {
    var date_entries = $('.date-entries').children();
    if (date_entries.length > 1) {
      date_entries.get(-1).remove();
    }
  })
})

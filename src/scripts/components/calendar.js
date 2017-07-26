import $ from "jquery";
import fullCalendar from "fullcalendar";
import "fullcalendar/dist/fullcalendar.css";

$("#calendar").fullCalendar({
  events: getEvents,
  dayClick: onDayClick
});

function onDayClick(date, jsEvent, view) {
  console.log(date.format());
}

function getEvents(start, end, timezone, callback) {
  const endpoint = "/wp-json/wp/v2/event";

  $.ajax({
    url: endpoint,
    success: (data) => {
      const events = [];

      for (const item of data) {
        const event = {
          title: item.title.rendered,
          start: $.fullCalendar.moment(item.acf.start_date),
          end: $.fullCalendar.moment(item.acf.end_date),
          url: item.link
        };

        events.push(event);
      }

      callback(events);
    }
  });
}

import $ from "jquery";
import fullCalendar from "fullcalendar";
import "fullcalendar/dist/fullcalendar.css";
import "../../styles/lib/fullcalendar.css";

$("#calendar").fullCalendar({
  events: getEvents,
  eventRender: function (event, element, view) {
    const dateString = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
    view.el.find(`.fc-day[data-date="${dateString}"]`).addClass("fc-event-day");
    view.el.find(`.fc-day-top[data-date="${dateString}"]`).addClass("fc-event-day-top");
  },
  height: "auto",
  header: false,
  dayNamesShort: ["S", "M", "T", "W", "T", "F", "S"],
  // dayClick: onDayClick,
  eventClick: onDayClick,
});

function onDayClick(event, jsEvent, view) {
  console.log(event);
}

function getEvents(start, end, timezone, callback) {
  const endpoint = "/wp-json/wp/v2/event";

  // if (category_id) {
  //   const endpoint2 = "/wp-json/wp/v2/event_category?slug=expo";
  //   endpoint += `?event_category=${category_id}`;
  // }

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

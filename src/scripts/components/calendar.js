import $ from "jquery";
import fullCalendar from "fullcalendar";
import "fullcalendar/dist/fullcalendar.css";
import "../../styles/lib/fullcalendar.css";

const pathname = window.location.pathname;
const yearMonth = pathname.match(/\/(\d+)\/(\d+)$/);
let defaultDate;
if (yearMonth) {
  const year = yearMonth[1];
  const month = yearMonth[2];
  defaultDate = $.fullCalendar.moment(`${year}-${month}`);
} else {
  defaultDate = $.fullCalendar.moment();
}

$("#fullcalendar").fullCalendar({
  events: getEvents,
  defaultDate,
  eventRender: renderEvent,
  height: "auto",
  header: false,
  dayNamesShort: ["S", "M", "T", "W", "T", "F", "S"],
  dayClick: onDayClick
});

function renderEvent(event, element, view) {
  const dateString = $.fullCalendar.formatDate(event.start, "Y-MM-DD");
  const $dayTopEvent = view.el.find(`.fc-day-top[data-date="${dateString}"]`);
  $dayTopEvent.addClass("fc-event-day-top");
  $dayTopEvent.attr("data-event-url", event.url);
}

function onDayClick(day, jsEvent, view) {
  const $target = $(jsEvent.target);
  if ($target.hasClass("fc-event-day-top")) {
    const url = $target.attr("data-event-url");
    window.location.href = url;
  }
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

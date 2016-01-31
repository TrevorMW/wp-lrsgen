<?php
/**
 *
 * @package WordPress
 * @subpackage themename
 */

global $page_type;

get_header();

$dash = new Dashboard(); ?>

  <div class="wrapper white dashboard-global-data">
    <div class="table">
      <div class="table-cell">
        <h2>Weekly</h2>
      </div>
      <div class="table-cell">
        <h2>Monthly</h2>
      </div>
      <div class="table-cell">
        <h2>Hotel Data:</h2>
      </div>
    </div>

  </div>

  <div class="wrapper table page-forms dashboard-form">
    <div class="table-cell ">

    </div>
    <div class="table-cell ">
      <form data-ajax-form data-action="filter_dashboard_dates" data-target="dashboard-days">
        <ul class="inline">
          <li>
            <select name="dashboard_date_count" data-fireable-input>
              <option value="7" selected>Week</option>
              <option value="31">Month</option>
              <option value="93">3 Months</option>
              <option value="93">6 Months</option>
            </select>
          </li>
        </ul>
      </form>
    </div>
  </div>

  <div class="wrapper dashboard-date-data">
    <ul data-updateable-content="dashboard-days"><?php echo $dash->get_dashboard_data(); ?></ul>
  </div>

<?php get_footer(); ?>

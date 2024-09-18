
import { render } from '@wordpress/element';
import { BrowserRouter} from 'react-router-dom';
import TopPickBySale from "./admin/toppickbysale";

/**
 * Import the stylesheet for the plugin.
 */
import './style/main.scss';
// Render the App component into the DOM
render(<BrowserRouter><TopPickBySale /></BrowserRouter>, document.getElementById('wc-admin-top-pick-by-sale'));
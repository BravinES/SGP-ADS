import ReactDOM from 'react-dom';

import App from './src';

if (document.getElementById('projectTasks')) {
    ReactDOM.render(<App />, document.getElementById('projectTasks'));
}

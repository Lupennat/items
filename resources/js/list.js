import IndexList from './components/IndexField';
import DetailList from './components/DetailField';
import FormList from './components/FormField';

Nova.booting((app, store) => {
    app.component('index-list', IndexList);
    app.component('detail-list', DetailList);
    app.component('form-list', FormList);
});

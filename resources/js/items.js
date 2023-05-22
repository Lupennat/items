import IndexItems from './components/IndexField';
import DetailItems from './components/DetailField';
import FormItems from './components/FormField';

Nova.booting((app, store) => {
    app.component('index-items', IndexItems);
    app.component('detail-items', DetailItems);
    app.component('form-items', FormItems);
});

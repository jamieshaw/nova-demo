import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-checkbox-field', IndexField)
  app.component('detail-checkbox-field', DetailField)
  app.component('form-checkbox-field', FormField)
})

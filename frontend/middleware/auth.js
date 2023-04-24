export default function ({
    store,
    route,
    redirect
}) {
    const allowedPaths = ['/', '/auth/login', '/auth/register', '/detail/:id', '/auth/mailLogin', '/auth/thanks']
    if (!store.state.user && !allowedPaths.includes(route.path)) {
        redirect('/')
    }
}
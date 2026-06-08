import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin-videos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VideoController::index
 * @see app/Http/Controllers/VideoController.php:16
 * @route '/admin-videos'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin-videos/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VideoController::create
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\VideoController::store
 * @see app/Http/Controllers/VideoController.php:44
 * @route '/admin-videos'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin-videos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\VideoController::store
 * @see app/Http/Controllers/VideoController.php:44
 * @route '/admin-videos'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::store
 * @see app/Http/Controllers/VideoController.php:44
 * @route '/admin-videos'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\VideoController::store
 * @see app/Http/Controllers/VideoController.php:44
 * @route '/admin-videos'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\VideoController::store
 * @see app/Http/Controllers/VideoController.php:44
 * @route '/admin-videos'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
export const show = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin-videos/{admin_video}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
show.url = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_video: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_video: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_video: args.admin_video,
                }

    return show.definition.url
            .replace('{admin_video}', parsedArgs.admin_video.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
show.get = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
show.head = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
    const showForm = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
        showForm.get = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VideoController::show
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}'
 */
        showForm.head = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
export const edit = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin-videos/{admin_video}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
edit.url = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_video: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_video: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_video: args.admin_video,
                }

    return edit.definition.url
            .replace('{admin_video}', parsedArgs.admin_video.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
edit.get = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
edit.head = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
    const editForm = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
        editForm.get = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\VideoController::edit
 * @see app/Http/Controllers/VideoController.php:0
 * @route '/admin-videos/{admin_video}/edit'
 */
        editForm.head = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
export const update = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin-videos/{admin_video}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
update.url = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_video: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_video: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_video: args.admin_video,
                }

    return update.definition.url
            .replace('{admin_video}', parsedArgs.admin_video.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
update.put = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
update.patch = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
    const updateForm = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
        updateForm.put = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\VideoController::update
 * @see app/Http/Controllers/VideoController.php:78
 * @route '/admin-videos/{admin_video}'
 */
        updateForm.patch = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\VideoController::destroy
 * @see app/Http/Controllers/VideoController.php:128
 * @route '/admin-videos/{admin_video}'
 */
export const destroy = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin-videos/{admin_video}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\VideoController::destroy
 * @see app/Http/Controllers/VideoController.php:128
 * @route '/admin-videos/{admin_video}'
 */
destroy.url = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { admin_video: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    admin_video: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        admin_video: args.admin_video,
                }

    return destroy.definition.url
            .replace('{admin_video}', parsedArgs.admin_video.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\VideoController::destroy
 * @see app/Http/Controllers/VideoController.php:128
 * @route '/admin-videos/{admin_video}'
 */
destroy.delete = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\VideoController::destroy
 * @see app/Http/Controllers/VideoController.php:128
 * @route '/admin-videos/{admin_video}'
 */
    const destroyForm = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\VideoController::destroy
 * @see app/Http/Controllers/VideoController.php:128
 * @route '/admin-videos/{admin_video}'
 */
        destroyForm.delete = (args: { admin_video: string | number } | [admin_video: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const adminVideos = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default adminVideos
<div class="card card-default">
  <div class="card-body row">
    {{-- Nombre --}}
    <div class="form-group col-md-4">
      <label for="name">Nombre</label>
      <input
        type="text"
        name="name"
        id="name"
        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
        value="{{ old('name', $usuario->name) }}"
        placeholder="Nombre completo"
      />
      @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Rol --}}
    <div class="form-group col-md-4">
      <label for="rol">Rol de usuario</label>
      <select
        name="rol"
        id="rol"
        class="form-control{{ $errors->has('rol') ? ' is-invalid' : '' }}"
      >
        <option value="">Selecciona un rol</option>
        @foreach($roles as $value => $label)
          <option
            value="{{ $value }}"
            {{ old('rol', $usuario->rol) === $value ? 'selected' : '' }}
          >{{ $label }}</option>
        @endforeach
      </select>
      @error('rol')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Correo --}}
    <div class="form-group col-md-4">
      <label for="email">Correo</label>
      <input
        type="email"
        name="email"
        id="email"
        class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
        value="{{ old('email', $usuario->email) }}"
        placeholder="email@dominio.com"
      />
      @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Contraseña --}}
    <div class="form-group col-md-4">
      <label for="password">Contraseña</label>
      <input
        type="password"
        name="password"
        id="password"
        class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
        placeholder="{{ isset($usuario->id) ? 'Dejar vacío para no cambiar' : 'Contraseña' }}"
      />
      @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>
  </div>

  <div class="card-footer text-right">
    <a href="{{ route('usuarios.index') }}" class="btn btn-danger mr-2">Cancel</a>
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</div>
